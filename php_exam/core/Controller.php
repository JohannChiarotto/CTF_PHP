<?php
// core/Controller.php

require_once __DIR__ . '/View.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Auth.php';

class Controller
{
    protected function view(string $template, array $params = []): void
    {
        View::render($template, $params);
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}

