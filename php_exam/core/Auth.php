<?php
// core/Auth.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Session.php';

class Auth
{
    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user['id'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function attempt(string $identifier, string $password): bool
    {
        $pdo = getPDO();

        $stmt = $pdo->prepare('SELECT * FROM `User` WHERE (username = ? OR email = ?) AND is_banned = 0 LIMIT 1');
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        Session::regenerate();
        unset($user['password']);
        Session::set('user', $user);

        return true;
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'admin';
    }

    public static function isCreatorOrAdmin(): bool
    {
        $user = self::user();
        return $user && in_array($user['role'], ['creator', 'admin'], true);
    }
}

