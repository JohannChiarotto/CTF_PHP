<?php
// models/Scoreboard.php

require_once __DIR__ . '/../config/database.php';

class Scoreboard
{
    public static function top(int $limit = 20): array
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT username, skill_level, score
            FROM `User`
            WHERE is_banned = 0 AND role != 'admin'
            ORDER BY score DESC, created_at ASC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}

