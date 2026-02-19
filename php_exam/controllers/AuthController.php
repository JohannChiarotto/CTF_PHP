<?php
// controllers/AuthController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Security.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/login', [
            'currentPage' => 'login',
            'pageStyles'  => ['/instance/css/register.css'],
        ]);
    }

    public function showRegister(): void
    {
        $this->view('auth/register', [
            'currentPage' => 'register',
            'pageStyles'  => ['/instance/css/register.css'],
        ]);
    }

    public function login(): void
    {
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->view('auth/login', ['error' => 'Session expirée. Veuillez réessayer.', 'currentPage' => 'login', 'pageStyles' => ['/instance/css/register.css']]);
            return;
        }

        $identifier = trim($_POST['identifier'] ?? '');
        $password   = $_POST['password'] ?? '';

        if (Auth::attempt($identifier, $password)) {
            $this->redirect('/');
        } else {
            $this->view('auth/login', ['error' => 'Identifiants invalides.', 'currentPage' => 'login', 'pageStyles' => ['/instance/css/register.css']]);
        }
    }

    public function register(): void
    {
        if (!$this->isPost() || !Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirect('/register');
        }

        $username    = trim($_POST['username'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $password    = $_POST['password'] ?? '';
        $password2   = $_POST['password_confirm'] ?? '';
        $bio         = trim($_POST['bio'] ?? '');
        $skill_level = $_POST['skill_level'] ?? 'Junior';

        $errors = [];
        if ($username === '' || $email === '' || $password === '' || $password2 === '') {
            $errors[] = 'Tous les champs obligatoires doivent être remplis.';
        }
        if ($password !== $password2) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide.';
        }

        if (User::findByUsernameOrEmail($username)) {
            $errors[] = 'Nom d\'utilisateur déjà utilisé.';
        }

        if (User::findByUsernameOrEmail($email)) {
            $errors[] = 'Email déjà utilisé.';
        }

        if ($errors) {
            $this->view('auth/register', [
                'errors'      => $errors,
                'currentPage' => 'register',
                'pageStyles'  => ['/instance/css/register.css'],
            ]);
            return;
        }

        try {
            User::create([
                'username'    => $username,
                'email'       => $email,
                'password'    => $password,
                'bio'         => $bio,
                'skill_level' => $skill_level,
            ]);

            // Rediriger vers la page de connexion après la création réussie
            $this->redirect('/login');
        } catch (Exception $e) {
            $this->view('auth/register', [
                'errors'      => ['Une erreur est survenue lors de la création du compte.'],
                'currentPage' => 'register',
                'pageStyles'  => ['/instance/css/register.css'],
            ]);
        }
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/');
    }
}

