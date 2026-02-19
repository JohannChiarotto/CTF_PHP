<?php
// controllers/ChallengeController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../models/Challenge.php';

class ChallengeController extends Controller
{
    public function showSell(): void
    {
        Middleware::requireAuth();
        $this->view('challenge/sell', [
            'currentPage' => 'sell',
            'pageStyles'  => ['/instance/css/forms.css'],
        ]);
    }

    public function store(): void
    {
        Middleware::requireAuth();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/sell');
        }

        $data = [
            'title'       => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'category'    => trim($_POST['category'] ?? ''),
            'difficulty'  => $_POST['difficulty'] ?? 'Noob',
            'price'       => (float) ($_POST['price'] ?? 0),
            'access_url'  => trim($_POST['access_url'] ?? ''),
            'image_url'   => trim($_POST['image_url'] ?? ''),
            'flag'        => trim($_POST['flag'] ?? ''),
        ];

        if ($data['title'] === '' || $data['description'] === '' || $data['flag'] === '') {
            $this->view('challenge/sell', [
                'error'       => 'Titre, description et flag sont obligatoires.',
                'currentPage' => 'sell',
                'pageStyles'  => ['/instance/css/forms.css'],
            ]);
            return;
        }

        $challengeId = Challenge::create($data, Auth::id());
        $this->redirect('/detail?id=' . $challengeId);
    }

    public function showEdit(): void
    {
        Middleware::requireAuth();
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/');
        }

        $challenge = Challenge::find($id);
        if (!$challenge) {
            $this->redirect('/');
        }

        if (!Auth::isAdmin() && !Challenge::isOwnedBy($id, Auth::id())) {
            http_response_code(403);
            echo 'Forbidden';
            return;
        }

        $this->view('challenge/edit', [
            'challenge'   => $challenge,
            'currentPage' => 'sell',
            'pageStyles'  => ['/instance/css/forms.css'],
        ]);
    }

    public function update(): void
    {
        Middleware::requireAuth();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/');
        }

        if (!Auth::isAdmin() && !Challenge::isOwnedBy($id, Auth::id())) {
            http_response_code(403);
            echo 'Forbidden';
            return;
        }

        $data = [
            'title'       => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'category'    => trim($_POST['category'] ?? ''),
            'difficulty'  => $_POST['difficulty'] ?? 'Noob',
            'price'       => (float) ($_POST['price'] ?? 0),
            'access_url'  => trim($_POST['access_url'] ?? ''),
            'image_url'   => trim($_POST['image_url'] ?? ''),
            'flag'        => trim($_POST['flag'] ?? ''),
            'is_active'   => isset($_POST['is_active']),
        ];

        Challenge::update($id, $data);
        $this->redirect('/detail?id=' . $id);
    }

    public function delete(): void
    {
        Middleware::requireAuth();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/');
        }

        if (!Auth::isAdmin() && !Challenge::isOwnedBy($id, Auth::id())) {
            http_response_code(403);
            echo 'Forbidden';
            return;
        }

        Challenge::delete($id);
        $this->redirect('/');
    }
}

