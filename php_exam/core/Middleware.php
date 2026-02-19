<?php
// core/Middleware.php

require_once __DIR__ . '/Auth.php';

class Middleware
{
    public static function requireAuth(): void
    {
        if (!Auth::check()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        if (!Auth::isAdmin()) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }

    public static function requireCreatorOrAdmin(): void
    {
        self::requireAuth();
        if (!Auth::isCreatorOrAdmin()) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }
}

