<?php
require_once 'db.php';
echo "<h1>Bienvenue sur la plateforme CTF !</h1>";
if ($mysqli) {
    echo "<p style='color:green'>✅ Connexion à la base de données réussie.</p>";
}
?>