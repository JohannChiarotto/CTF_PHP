<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = htmlspecialchars(trim($_POST['identifiant'])); // Peut être email ou username
    $password = $_POST['password'];

    if (empty($identifiant) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // 1. Chercher l'utilisateur par son pseudo OU son email 
        $stmt = $pdo->prepare("SELECT * FROM User WHERE username = ? OR email = ?");
        $stmt->execute([$identifiant, $identifiant]);
        $user = $stmt->fetch();

        // 2. Vérification sécurisée du mot de passe [cite: 23]
        if ($user && password_verify($password, $user['password'])) {
            // Succès : Initialisation de la session 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirection vers la Home [cite: 41]
            header("Location: index.php");
            exit();
        } else {
            // Échec : Message d'erreur générique pour la sécurité 
            $error = "Identifiants incorrects.";
        }
    }
}

include 'login.html';
?>