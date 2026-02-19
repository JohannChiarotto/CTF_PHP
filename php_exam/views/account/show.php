<?php
?>
<main class="container">
    <section class="hero" style="padding: 120px 0 40px;">
        <div class="hero-content">
            <h1 class="hero-title">Profil <span>public</span></h1>
            <p class="hero-subtitle"><?php echo Security::e($profile['username']); ?></p>
        </div>
    </section>

    <section class="challenges-section">
        <div class="account-card" style="max-width: 500px;">
            <h2>Statistiques</h2>
            <div class="profile-stat">
                <span>Niveau</span>
                <span class="value"><?php echo Security::e($profile['skill_level']); ?></span>
            </div>
            <div class="profile-stat">
                <span>Challenges créés</span>
                <span class="value"><?php echo (int) $profile['challenges_created']; ?></span>
            </div>
            <div class="profile-stat">
                <span>Challenges résolus</span>
                <span class="value"><?php echo (int) $profile['challenges_solved']; ?></span>
            </div>
        </div>
    </section>
</main>
