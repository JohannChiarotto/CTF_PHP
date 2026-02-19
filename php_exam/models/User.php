<?php
// models/User.php

require_once __DIR__ . '/../config/database.php';

class User
{
    public static function findByUsernameOrEmail(string $identifier): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM `User` WHERE username = ? OR email = ? LIMIT 1');
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            INSERT INTO `User` (username, email, password, bio, skill_level)
            VALUES (:username, :email, :password, :bio, :skill_level)
        ");
        $stmt->execute([
            'username'    => $data['username'],
            'email'       => $data['email'],
            'password'    => password_hash($data['password'], PASSWORD_DEFAULT),
            'bio'         => $data['bio'] ?? null,
            'skill_level' => $data['skill_level'] ?? 'Junior',
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function find(int $id): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM `User` WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findPublic(int $id): ?array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT id, username, profile_picture, skill_level,
                   (SELECT COUNT(*) FROM `Challenge` WHERE author_id = `User`.id) AS challenges_created,
                   (SELECT COUNT(*) FROM `Submission` WHERE user_id = `User`.id AND is_valid = 1) AS challenges_solved
            FROM `User`
            WHERE id = :id
        ");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function updateProfile(int $id, array $data): void
    {
        $pdo = getPDO();
        $updatePassword = !empty($data['password']);

        $sql = "
            UPDATE `User`
            SET email = :email,
                bio = :bio,
                skill_level = :skill_level
        ";
        if ($updatePassword) {
            $sql .= ', password = :password';
        }
        $sql .= ' WHERE id = :id';

        $params = [
            'email'       => $data['email'],
            'bio'         => $data['bio'] ?? null,
            'skill_level' => $data['skill_level'] ?? 'Junior',
            'id'          => $id,
        ];

        if ($updatePassword) {
            $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    public static function addBalance(int $id, float $amount): void
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('UPDATE `User` SET balance = balance + :amount WHERE id = :id');
        $stmt->execute(['amount' => $amount, 'id' => $id]);
    }

    public static function allForAdmin(): array
    {
        $pdo = getPDO();
        $stmt = $pdo->query('SELECT * FROM `User` ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function updateRole(int $id, string $role): void
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('UPDATE `User` SET role = :role WHERE id = :id');
        $stmt->execute(['role' => $role, 'id' => $id]);
    }

    public static function ban(int $id, bool $ban = true): void
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('UPDATE `User` SET is_banned = :ban WHERE id = :id');
        $stmt->execute(['ban' => $ban ? 1 : 0, 'id' => $id]);
    }

    public static function resetScoreAndBalance(int $id): void
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare('UPDATE `User` SET score = 0, balance = 0 WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

