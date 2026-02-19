<?php
$pageStyles = $pageStyles ?? [];
$baseStyles = ['/instance/css/home.css'];
$allStyles = array_merge($baseStyles, $pageStyles);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? Security::e($pageTitle) . ' - ' : ''; ?>Bug Bounty CTF</title>
    <?php foreach (array_unique($allStyles) as $stylePath): ?>
        <link rel="stylesheet" href="<?php echo $baseUrl . $stylePath; ?>">
    <?php endforeach; ?>
</head>
<body>
<div class="background">
    <div class="circuit-board"></div>
</div>
<?php include __DIR__ . '/navbar.php'; ?>
