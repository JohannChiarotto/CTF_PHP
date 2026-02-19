<?php
$currentPage = $currentPage ?? '';
?>
<div class="navbar">
    <div class="nav-container">
        <a href="<?php echo $baseUrl; ?>/" class="logo">
            <div class="logo-icon"></div>
            <div class="logo-text">BugBounty<span>CTF</span></div>
        </a>
        <div class="nav-links">
            <a href="<?php echo $baseUrl; ?>/" class="nav-link <?php echo $currentPage === 'home' ? 'active' : ''; ?>">Accueil</a>
            <a href="<?php echo $baseUrl; ?>/scoreboard" class="nav-link <?php echo $currentPage === 'scoreboard' ? 'active' : ''; ?>">Scoreboard</a>
            <?php if (Auth::check()): ?>
                <a href="<?php echo $baseUrl; ?>/sell" class="nav-link <?php echo $currentPage === 'sell' ? 'active' : ''; ?>">Créer un challenge</a>
                <a href="<?php echo $baseUrl; ?>/cart" class="nav-link <?php echo $currentPage === 'cart' ? 'active' : ''; ?>">Panier</a>
                <a href="<?php echo $baseUrl; ?>/account" class="nav-link <?php echo $currentPage === 'account' ? 'active' : ''; ?>">Mon compte</a>
                <?php if (Auth::isAdmin()): ?>
                    <a href="<?php echo $baseUrl; ?>/admin" class="nav-link <?php echo in_array($currentPage, ['admin','admin-users','admin-challenges']) ? 'active' : ''; ?>">Admin</a>
                <?php endif; ?>
                <a href="<?php echo $baseUrl; ?>/logout" class="nav-link">Déconnexion</a>
            <?php else: ?>
                <a href="<?php echo $baseUrl; ?>/login" class="nav-link <?php echo $currentPage === 'login' ? 'active' : ''; ?>">Connexion</a>
                <a href="<?php echo $baseUrl; ?>/register" class="nav-link <?php echo $currentPage === 'register' ? 'active' : ''; ?>">Inscription</a>
            <?php endif; ?>
        </div>
        <div class="nav-auth">
            <?php if (Auth::check()): ?>
                <a href="<?php echo $baseUrl; ?>/account" class="btn-register"><?php echo Security::e(Auth::user()['username'] ?? 'Compte'); ?></a>
            <?php else: ?>
                <a href="<?php echo $baseUrl; ?>/login" class="btn-login">Connexion</a>
                <a href="<?php echo $baseUrl; ?>/register" class="btn-register">Commencer</a>
            <?php endif; ?>
        </div>
    </div>
</div>
