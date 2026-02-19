<?php
// controllers/AdminController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Challenge.php';

class AdminController extends Controller
{
    public function index(): void
    {
        Middleware::requireAdmin();
        $this->view('admin/index', [
            'currentPage' => 'admin',
            'pageStyles'  => ['/instance/css/admin.css'],
        ]);
    }

    public function users(): void
    {
        Middleware::requireAdmin();
        $users = User::allForAdmin();
        $this->view('admin/users', ['users' => $users]);
    }

    public function challenges(): void
    {
        Middleware::requireAdmin();
        $pdo = getPDO();
        $stmt = $pdo->query("
            SELECT c.*, u.username AS author_name
            FROM `Challenge` c
            JOIN `User` u ON c.author_id = u.id
            ORDER BY c.created_at DESC
        ");
        $challenges = $stmt->fetchAll();
        $this->view('admin/challenges', [
            'challenges'  => $challenges,
            'currentPage' => 'admin-challenges',
            'pageStyles'  => ['/instance/css/admin.css'],
        ]);
    }

    public function updateRole(): void
    {
        Middleware::requireAdmin();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/admin/users');
        }

        $id   = (int) ($_POST['user_id'] ?? 0);
        $role = $_POST['role'] ?? 'user';

        if ($id > 0 && in_array($role, ['user', 'creator', 'admin'], true)) {
            User::updateRole($id, $role);
        }

        $this->redirect('/admin/users');
    }

    public function banUser(): void
    {
        Middleware::requireAdmin();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/admin/users');
        }

        $id  = (int) ($_POST['user_id'] ?? 0);
        $ban = isset($_POST['ban']);

        if ($id > 0) {
            User::ban($id, $ban);
        }

        $this->redirect('/admin/users');
    }

    public function resetUser(): void
    {
        Middleware::requireAdmin();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/admin/users');
        }

        $id = (int) ($_POST['user_id'] ?? 0);
        if ($id > 0) {
            User::resetScoreAndBalance($id);
        }

        $this->redirect('/admin/users');
    }

    public function toggleChallenge(): void
    {
        Middleware::requireAdmin();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/admin/challenges');
        }

        $id = (int) ($_POST['challenge_id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/admin/challenges');
        }

        $challenge = Challenge::find($id);
        if (!$challenge) {
            $this->redirect('/admin/challenges');
        }

        Challenge::update($id, [
            'title'       => $challenge['title'],
            'description' => $challenge['description'],
            'category'    => $challenge['category'],
            'difficulty'  => $challenge['difficulty'],
            'price'       => $challenge['price'],
            'access_url'  => $challenge['access_url'],
            'image_url'   => $challenge['image_url'],
            'flag'        => '',
            'is_active'   => !$challenge['is_active'],
        ]);

        $this->redirect('/admin/challenges');
    }

    public function deleteChallenge(): void
    {
        Middleware::requireAdmin();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/admin/challenges');
        }

        $id = (int) ($_POST['challenge_id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/admin/challenges');
        }

        $challenge = Challenge::find($id);
        if (!$challenge) {
            $this->redirect('/admin/challenges');
        }

        Challenge::delete($id);
        $this->redirect('/admin/challenges');
    }
}

