<?php
// config/database.php
// Gestion de la connexion à la base de données basée sur le travail du collègue
// Adapté pour utiliser PDO pour compatibilité avec le projet existant

require_once __DIR__ . '/config.php';

function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            $pdo->exec("SET NAMES utf8mb4");
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    return $pdo;
}

/**
 * Alternative MySQLi pour compatibilité avec le code du collègue si nécessaire
 */
function getMySQLi(): mysqli
{
    static $mysqli = null;

    if ($mysqli === null) {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($mysqli->connect_error) {
            die("Erreur : " . $mysqli->connect_error);
        }
        $mysqli->set_charset("utf8mb4");
    }

    return $mysqli;
}

