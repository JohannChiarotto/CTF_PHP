<?php
// models/CartItem.php

require_once __DIR__ . '/../config/database.php';

class CartItem
{
    public static function getUserCart(int $userId): array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT c.id AS challenge_id, c.title, c.price, cart.quantity
            FROM `Cart` cart
            JOIN `Challenge` c ON cart.challenge_id = c.id
            WHERE cart.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function addToCart(int $userId, int $challengeId, int $qty = 1): void
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO `Cart` (user_id, challenge_id, quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + ?
        ");
        $stmt->execute([$userId, $challengeId, $qty, $qty]);
    }

    public static function updateQuantity(int $userId, int $challengeId, int $qty): void
    {
        $pdo = getPDO();
        if ($qty <= 0) {
            self::removeFromCart($userId, $challengeId);
            return;
        }
        $stmt = $pdo->prepare("
            UPDATE `Cart` SET quantity = ? WHERE user_id = ? AND challenge_id = ?
        ");
        $stmt->execute([$qty, $userId, $challengeId]);
    }

    public static function removeFromCart(int $userId, int $challengeId): void
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('DELETE FROM `Cart` WHERE user_id = ? AND challenge_id = ?');
        $stmt->execute([$userId, $challengeId]);
    }

    public static function clearCart(int $userId): void
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('DELETE FROM `Cart` WHERE user_id = ?');
        $stmt->execute([$userId]);
    }
}

