<?php
// models/Submission.php

require_once __DIR__ . '/../config/database.php';

class Submission
{
    public static function submitFlag(int $userId, int $challengeId, string $flag, string $correctFlagHash): bool
    {
        $isValid = password_verify($flag, $correctFlagHash);

        $pdo = getPDO();
        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare("
                INSERT INTO `Submission` (user_id, challenge_id, flag_submitted, is_valid)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $userId,
                $challengeId,
                $flag,
                $isValid ? 1 : 0,
            ]);

            if ($isValid) {
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) FROM `Submission`
                    WHERE user_id = ? AND challenge_id = ? AND is_valid = 1
                ");
                $stmt->execute([$userId, $challengeId]);
                $count = (int) $stmt->fetchColumn();

                if ($count === 1) {
                    $scoreToAdd = 10;
                    $stmt = $pdo->prepare('SELECT difficulty FROM `Challenge` WHERE id = ?');
                    $stmt->execute([$challengeId]);
                    $difficulty = $stmt->fetchColumn();

                    switch ($difficulty) {
                        case 'Mid':
                            $scoreToAdd = 20;
                            break;
                        case 'Ardu':
                            $scoreToAdd = 40;
                            break;
                        case 'Fou':
                            $scoreToAdd = 60;
                            break;
                        case 'CybersecurityTitle':
                            $scoreToAdd = 100;
                            break;
                    }

                    $stmt = $pdo->prepare('UPDATE `User` SET score = score + ? WHERE id = ?');
                    $stmt->execute([$scoreToAdd, $userId]);
                }
            }

            $pdo->commit();
            return $isValid;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function hasValidSubmission(int $userId, int $challengeId): bool
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM `Submission`
            WHERE user_id = ? AND challenge_id = ? AND is_valid = 1
        ");
        $stmt->execute([$userId, $challengeId]);
        return $stmt->fetchColumn() > 0;
    }
}

