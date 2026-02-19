<?php
// models/Invoice.php

require_once __DIR__ . '/../config/database.php';

class Invoice
{
    public static function createFromCart(
        int $userId,
        array $cartItems,
        string $address,
        string $city,
        string $zip
    ): int {
        $pdo = getPDO();
        $pdo->beginTransaction();

        try {
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $stmt = $pdo->prepare('SELECT balance FROM `User` WHERE id = :id FOR UPDATE');
            $stmt->execute(['id' => $userId]);
            $balance = (float) $stmt->fetchColumn();

            if ($balance < $total) {
                throw new Exception('Solde insuffisant');
            }

            $stmt = $pdo->prepare('UPDATE `User` SET balance = balance - :amount WHERE id = :id');
            $stmt->execute(['amount' => $total, 'id' => $userId]);

            $stmt = $pdo->prepare("
                INSERT INTO `Invoice` (user_id, amount, billing_address, billing_city, billing_zip)
                VALUES (:uid, :amount, :addr, :city, :zip)
            ");
            $stmt->execute([
                'uid'    => $userId,
                'amount' => $total,
                'addr'   => $address,
                'city'   => $city,
                'zip'    => $zip,
            ]);

            $invoiceId = (int) $pdo->lastInsertId();

            $stmtItem = $pdo->prepare("
                INSERT INTO `InvoiceItem` (invoice_id, challenge_id, quantity, unit_price)
                VALUES (:iid, :cid, :qty, :price)
            ");

            $stmtUserChallenge = $pdo->prepare("
                INSERT INTO `UserChallenge` (user_id, challenge_id)
                VALUES (:uid, :cid)
                ON DUPLICATE KEY UPDATE acquired_at = acquired_at
            ");

            foreach ($cartItems as $item) {
                $stmtItem->execute([
                    'iid'   => $invoiceId,
                    'cid'   => $item['challenge_id'],
                    'qty'   => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $stmtUserChallenge->execute([
                    'uid' => $userId,
                    'cid' => $item['challenge_id'],
                ]);
            }

            $pdo->commit();
            return $invoiceId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function findByUser(int $userId): array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM `Invoice` WHERE user_id = :uid ORDER BY date DESC');
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }
}

