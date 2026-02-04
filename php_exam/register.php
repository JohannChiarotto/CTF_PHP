<?php
session_start();
require_once 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Récupération et nettoyage des données
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $skill_level = $_POST['skill_level'] ?? 'Junior';

    // 2. Vérifications de base
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Tous les champs obligatoires doivent être remplis.";
    } elseif ($password !== $password_confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // 3. Vérifier si l'utilisateur ou l'email existe déjà
        $check = $pdo->prepare("SELECT id FROM User WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);
        
        if ($check->rowCount() > 0) {
            $error = "Le pseudo ou l'email est déjà utilisé.";
        } else {
            // 4. Hachage du mot de passe 
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // 5. Insertion en base de données
            $ins = $pdo->prepare("INSERT INTO User (username, email, password, skill_level, balance, role, created_at) VALUES (?, ?, ?, ?, 0, 'user', NOW())");
            
            if ($ins->execute([$username, $email, $hashedPassword, $skill_level])) {
                // 6. Connexion automatique 
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'user';

                // Redirection vers l'accueil 
                header("Location: index.php");
                exit();
            } else {
                $error = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}

include 'register.html';
?>