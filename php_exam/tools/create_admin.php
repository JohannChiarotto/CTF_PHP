<?php
// tools/create_admin.php
// Usage: php tools/create_admin.php [username] [email] [password]

require_once __DIR__ . '/../config/database.php';

$username = $argv[1] ?? 'admin';
$email = $argv[2] ?? 'admin@gmail.com';
$password = $argv[3] ?? 'Azerty1234!';

// Basic validation
if (strlen($username) < 3 || strlen($password) < 8) {
    fwrite(STDERR, "Username must be >=3 chars and password >=8 chars\n");
    exit(1);
}

$pdo = getPDO();

// Check if user exists
$stmt = $pdo->prepare('SELECT id FROM `User` WHERE username = :u OR email = :e LIMIT 1');
$stmt->execute(['u' => $username, 'e' => $email]);
$existing = $stmt->fetch();
if ($existing) {
    fwrite(STDERR, "User with same username or email already exists (id: " . $existing['id'] . "). Aborting.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$insert = $pdo->prepare("INSERT INTO `User` (username,email,password,role,profile_picture,skill_level,created_at) VALUES (:u,:e,:p,'admin','default.png','Senior',NOW())");
$insert->execute(['u' => $username, 'e' => $email, 'p' => $hash]);

echo "Admin user created: id=" . $pdo->lastInsertId() . " username={$username} email={$email}\n";

?>
