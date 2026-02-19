<?php
// controllers/CartController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../models/CartItem.php';
require_once __DIR__ . '/../models/Invoice.php';

class CartController extends Controller
{
    public function index(): void
    {
        Middleware::requireAuth();
        $items = CartItem::getUserCart(Auth::id());
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $this->view('cart/index', [
            'items'       => $items,
            'total'      => $total,
            'currentPage' => 'cart',
        ]);
    }

    public function add(): void
    {
        Middleware::requireAuth();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/cart');
        }

        $challengeId = (int) ($_POST['challenge_id'] ?? 0);
        if ($challengeId > 0) {
            CartItem::addToCart(Auth::id(), $challengeId);
        }

        $this->redirect('/cart');
    }

    public function update(): void
    {
        Middleware::requireAuth();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/cart');
        }

        $challengeId = (int) ($_POST['challenge_id'] ?? 0);
        $qty         = (int) ($_POST['quantity'] ?? 1);
        if ($challengeId > 0) {
            CartItem::updateQuantity(Auth::id(), $challengeId, $qty);
        }

        $this->redirect('/cart');
    }

    public function remove(): void
    {
        Middleware::requireAuth();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/cart');
        }

        $challengeId = (int) ($_POST['challenge_id'] ?? 0);
        if ($challengeId > 0) {
            CartItem::removeFromCart(Auth::id(), $challengeId);
        }

        $this->redirect('/cart');
    }

    public function showValidate(): void
    {
        Middleware::requireAuth();
        $items = CartItem::getUserCart(Auth::id());
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $this->view('cart/validate', [
            'items'       => $items,
            'total'      => $total,
            'currentPage' => 'cart',
            'pageStyles'  => ['/instance/css/register.css'],
        ]);
    }

    public function validate(): void
    {
        Middleware::requireAuth();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/cart/validate');
        }

        $address = trim($_POST['billing_address'] ?? '');
        $city    = trim($_POST['billing_city'] ?? '');
        $zip     = trim($_POST['billing_zip'] ?? '');

        if ($address === '' || $city === '' || $zip === '') {
            $this->redirect('/cart/validate');
        }

        $items = CartItem::getUserCart(Auth::id());
        if (empty($items)) {
            $this->redirect('/cart');
        }

        try {
            $invoiceId = Invoice::createFromCart(Auth::id(), $items, $address, $city, $zip);
            CartItem::clearCart(Auth::id());

            $total = 0;
            foreach ($items as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $this->view('cart/validate', [
                'items'       => $items,
                'total'      => $total,
                'success'    => true,
                'invoiceId'  => $invoiceId,
                'currentPage'=> 'cart',
                'pageStyles' => ['/instance/css/register.css'],
            ]);
        } catch (Exception $e) {
            $this->view('cart/validate', [
                'items'       => $items,
                'error'      => $e->getMessage(),
                'currentPage'=> 'cart',
                'pageStyles' => ['/instance/css/register.css'],
            ]);
        }
    }
}

