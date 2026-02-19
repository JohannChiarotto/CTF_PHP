<?php
// models/Challenge.php

require_once __DIR__ . '/../config/database.php';

class Challenge
{
    public static function all(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query("
            SELECT c.*, u.username AS author_name
            FROM `Challenge` c
            JOIN `User` u ON c.author_id = u.id
            WHERE c.is_active = 1
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT c.*, u.username AS author_name
            FROM `Challenge` c
            JOIN `User` u ON c.author_id = u.id
            WHERE c.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data, int $authorId): int
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO `Challenge` (title, description, category, difficulty, price, author_id, image_url, access_url, flag_hash)
            VALUES (:title, :description, :category, :difficulty, :price, :author_id, :image_url, :access_url, :flag_hash)
        ");
        $stmt->execute([
            'title'       => $data['title'],
            'description' => $data['description'],
            'category'    => $data['category'],
            'difficulty'  => $data['difficulty'],
            'price'       => $data['price'],
            'author_id'   => $authorId,
            'image_url'   => $data['image_url'] ?? null,
            'access_url'  => $data['access_url'],
            'flag_hash'   => password_hash($data['flag'], PASSWORD_DEFAULT),
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = getPDO();
        $sql = "
            UPDATE `Challenge`
            SET title = :title,
                description = :description,
                category = :category,
                difficulty = :difficulty,
                price = :price,
                image_url = :image_url,
                access_url = :access_url,
                is_active = :is_active
        ";
        $params = [
            'title'       => $data['title'],
            'description' => $data['description'],
            'category'    => $data['category'],
            'difficulty'  => $data['difficulty'],
            'price'       => $data['price'],
            'image_url'   => $data['image_url'] ?? null,
            'access_url'  => $data['access_url'],
            'is_active'   => !empty($data['is_active']) ? 1 : 0,
            'id'          => $id,
        ];

        if (!empty($data['flag'])) {
            $sql .= ', flag_hash = :flag_hash';
            $params['flag_hash'] = password_hash($data['flag'], PASSWORD_DEFAULT);
        }

        $sql .= ' WHERE id = :id';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    public static function delete(int $id): void
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('DELETE FROM `Challenge` WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public static function isOwnedBy(int $challengeId, int $userId): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM `Challenge` WHERE id = :cid AND author_id = :uid');
        $stmt->execute(['cid' => $challengeId, 'uid' => $userId]);
        return $stmt->fetchColumn() > 0;
    }
}

