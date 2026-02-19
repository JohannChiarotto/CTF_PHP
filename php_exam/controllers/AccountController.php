<?php
// controllers/AccountController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../config/database.php';

class AccountController extends Controller
{
    public function index(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id > 0 && (!Auth::check() || $id !== Auth::id())) {
            $userPublic = User::findPublic($id);
            if (!$userPublic) {
                $this->redirect('/');
            }
            $this->view('account/show', [
                'profile'     => $userPublic,
                'currentPage' => 'account',
                'pageStyles'  => ['/instance/css/account.css'],
            ]);
            return;
        }

        Middleware::requireAuth();
        $user = User::find(Auth::id());
        if (!$user) {
            $this->redirect('/login');
        }

        $pdo = getPDO();

        $stmt = $pdo->prepare('SELECT * FROM `Challenge` WHERE author_id = ?');
        $stmt->execute([Auth::id()]);
        $createdChallenges = $stmt->fetchAll();

        $stmt = $pdo->prepare("
            SELECT c.*,
                   (SELECT COUNT(*) FROM `Submission` s
                    WHERE s.user_id = ? AND s.challenge_id = c.id AND s.is_valid = 1) AS solved
            FROM `UserChallenge` uc
            JOIN `Challenge` c ON uc.challenge_id = c.id
            WHERE uc.user_id = ?
        ");
        $stmt->execute([Auth::id(), Auth::id()]);
        $boughtChallenges = $stmt->fetchAll();

        $invoices = Invoice::findByUser(Auth::id());

        $this->view('account/index', [
            'user'              => $user,
            'createdChallenges' => $createdChallenges,
            'boughtChallenges'  => $boughtChallenges,
            'invoices'          => $invoices,
            'currentPage'       => 'account',
            'pageStyles'        => ['/instance/css/account.css'],
        ]);
    }

    public function update(): void
    {
        Middleware::requireAuth();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/account');
        }

        $data = [
            'email'       => trim($_POST['email'] ?? ''),
            'bio'         => trim($_POST['bio'] ?? ''),
            'skill_level' => $_POST['skill_level'] ?? 'Junior',
            'password'    => $_POST['password'] ?? '',
        ];

        User::updateProfile(Auth::id(), $data);
        $this->redirect('/account');
    }

    public function addBalance(): void
    {
        Middleware::requireAuth();
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/account');
        }

        $amount = (float) ($_POST['amount'] ?? 0);
        if ($amount > 0) {
            User::addBalance(Auth::id(), $amount);
        }
        $this->redirect('/account');
    }
}

