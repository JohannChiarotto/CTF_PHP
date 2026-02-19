<?php
?>
<main class="container">
    <section class="hero" style="padding: 120px 0 40px;">
        <div class="hero-content" style="text-align: left; max-width: 100%;">
            <h1 class="hero-title"><?php echo Security::e($challenge['title']); ?></h1>
            <p class="hero-subtitle">
                <?php echo Security::e($challenge['category']); ?> • <?php echo Security::e($challenge['difficulty']); ?><?php if (!$challenge['userHasBought']): ?> • <?php echo number_format($challenge['price'], 2, ',', ' '); ?> €<?php else: ?> • ✓ Acheté<?php endif; ?>
            </p>
        </div>
    </section>

    <section class="challenges-section">
        <article class="challenge-card" style="max-width: 900px;">
            <div class="card-header">
                <span class="challenge-category"><?php echo Security::e($challenge['category']); ?></span>
                <span class="challenge-difficulty"><?php echo Security::e($challenge['difficulty']); ?></span>
            </div>

            <p class="challenge-description" style="margin-bottom: 25px;">
                <?php echo nl2br(Security::e($challenge['description'])); ?>
            </p>

            <div class="challenge-meta">
                <div class="meta-item">Auteur : <?php echo Security::e($challenge['author_name']); ?></div>
                <div class="meta-item">URL : <?php echo Security::e($challenge['access_url'] ?: '-'); ?></div>
            </div>

            <?php if (!empty($flagResult)): ?>
                <p style="color: <?php echo strpos($flagResult, 'correct !') !== false ? '#22c55e' : '#f87171'; ?>; margin: 20px 0;">
                    <?php echo Security::e($flagResult); ?>
                </p>
            <?php endif; ?>

            <div class="challenge-footer" style="margin-top: 30px; flex-wrap: wrap; gap: 15px;">
                <?php if (Auth::check()): ?>
                    <?php if ($challenge['userHasBought']): ?>
                        <!-- Utilisateur a acheté - afficher le formulaire de flag -->
                        <form method="post" action="<?php echo $baseUrl; ?>/submit-flag" style="display: inline-flex; gap: 10px; align-items: center;">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                            <input type="hidden" name="challenge_id" value="<?php echo (int) $challenge['id']; ?>">
                            <input type="text" name="flag" placeholder="FLAG{}" required style="padding: 10px 15px; background: rgba(30,41,59,0.6); border: 2px solid rgba(0,255,255,0.2); border-radius: 8px; color: #fff; min-width: 200px;">
                            <button type="submit" class="btn-start">Soumettre le flag</button>
                        </form>
                    <?php else: ?>
                        <!-- Utilisateur n'a pas acheté - afficher le bouton panier -->
                        <form method="post" action="<?php echo $baseUrl; ?>/cart/add" style="display: inline;">
                            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                            <input type="hidden" name="challenge_id" value="<?php echo (int) $challenge['id']; ?>">
                            <button type="submit" class="btn-start">Ajouter au panier</button>
                        </form>
                    <?php endif; ?>

                    <?php if (Auth::id() === (int) $challenge['author_id'] || Auth::isAdmin()): ?>
                        <a href="<?php echo $baseUrl; ?>/edit?id=<?php echo (int) $challenge['id']; ?>" class="btn-login" style="text-decoration: none;">Modifier ce challenge</a>
                    <?php endif; ?>
                <?php else: ?>
                    <p style="color: #94a3b8;">Connectez-vous pour acheter ou résoudre ce challenge.</p>
                <?php endif; ?>
            </div>
        </article>
    </section>
</main>
