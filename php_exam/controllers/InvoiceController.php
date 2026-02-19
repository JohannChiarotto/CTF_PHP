<?php
// controllers/InvoiceController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../core/Security.php';

class InvoiceController extends Controller
{
    public function show(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            $this->redirect('/account');
        }

        $invoice = Invoice::findById($id);
        if (!$invoice) {
            $this->redirect('/account');
        }

        // Only owner can view/download their invoice
        Middleware::requireAuth();
        if ($invoice['user_id'] !== Auth::id()) {
            $this->redirect('/account');
        }

        $items = Invoice::getItems($id);

        // If download requested, render raw invoice and send as attachment
        if (isset($_GET['download']) && $_GET['download'] == '1') {
            $fileName = 'invoice-' . $invoice['id'] . '.html';
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            // include a minimal standalone view
            $baseUrl = BASE_URL;
            include __DIR__ . '/../views/invoice/file.php';
            exit;
        }

        $this->view('invoice/show', [
            'invoice' => $invoice,
            'items'   => $items,
            'currentPage' => 'account',
        ]);
    }
}
