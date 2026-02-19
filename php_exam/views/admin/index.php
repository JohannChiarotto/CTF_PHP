<?php
?>
<main class="container">
    <section class="hero" style="padding: 120px 0 40px;">
        <div class="hero-content">
            <h1 class="hero-title">Espace <span>administrateur</span></h1>
            <p class="hero-subtitle">Gérez la plateforme Bug Bounty CTF</p>
        </div>
    </section>

    <section class="challenges-section">
        <div class="admin-links">
            <a href="<?php echo $baseUrl; ?>/admin/users" class="admin-link-card">
                <h3>👥 Gérer les utilisateurs</h3>
                <p>Modifier les rôles, bannir, réinitialiser les scores</p>
            </a>
            <a href="<?php echo $baseUrl; ?>/admin/challenges" class="admin-link-card">
                <h3>🎯 Gérer les challenges</h3>
                <p>Activer ou désactiver les challenges</p>
            </a>
        </div>
    </section>
</main>
