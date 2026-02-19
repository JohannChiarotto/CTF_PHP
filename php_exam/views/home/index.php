<?php
?>
<main class="container">
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">
                Rejoignez l'arène <span>Bug Bounty</span>
            </h1>
            <p class="hero-subtitle">
                Entraînez-vous sur des challenges réalistes, gagnez des points et grimpez dans le classement.
            </p>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($challenges); ?></div>
                    <div class="stat-label">Challenges disponibles</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Plateforme en ligne</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">∞</div>
                    <div class="stat-label">Tentatives</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section d'accès rapide pour utilisateurs connectés -->
    <?php if (Auth::check()): ?>
    <section class="quick-access-section" style="background: linear-gradient(135deg, rgba(0,255,255,0.1) 0%, rgba(139,92,246,0.1) 100%); padding: 40px 0; margin: 40px 0; border-radius: 15px; border: 1px solid rgba(0,255,255,0.2);">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 30px; color: #00ffff;">Accès rapide</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 0 20px;">
                <a href="<?php echo $baseUrl; ?>/sell" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px; background: rgba(30,41,59,0.6); border: 2px solid rgba(139,92,246,0.3); border-radius: 12px; text-decoration: none; color: #fff; transition: all 0.3s;">
                    <span style="font-size: 40px; margin-bottom: 15px;">🎯</span>
                    <h3 style="margin: 0 0 10px 0; text-align: center;">Créer un challenge</h3>
                    <p style="margin: 0; font-size: 13px; color: #94a3b8; text-align: center;">Partagez vos défis</p>
                </a>
                <a href="<?php echo $baseUrl; ?>/cart" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px; background: rgba(30,41,59,0.6); border: 2px solid rgba(34,197,94,0.3); border-radius: 12px; text-decoration: none; color: #fff; transition: all 0.3s;">
                    <span style="font-size: 40px; margin-bottom: 15px;">🛒</span>
                    <h3 style="margin: 0 0 10px 0; text-align: center;">Panier</h3>
                    <p style="margin: 0; font-size: 13px; color: #94a3b8; text-align: center;">Gérez vos achats</p>
                </a>
                <a href="<?php echo $baseUrl; ?>/account" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px; background: rgba(30,41,59,0.6); border: 2px solid rgba(59,130,246,0.3); border-radius: 12px; text-decoration: none; color: #fff; transition: all 0.3s;">
                    <span style="font-size: 40px; margin-bottom: 15px;">👤</span>
                    <h3 style="margin: 0 0 10px 0; text-align: center;">Mon compte</h3>
                    <p style="margin: 0; font-size: 13px; color: #94a3b8; text-align: center;">Profil et paramètres</p>
                </a>
                <a href="<?php echo $baseUrl; ?>/scoreboard" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px; background: rgba(30,41,59,0.6); border: 2px solid rgba(236,72,153,0.3); border-radius: 12px; text-decoration: none; color: #fff; transition: all 0.3s;">
                    <span style="font-size: 40px; margin-bottom: 15px;">🏆</span>
                    <h3 style="margin: 0 0 10px 0; text-align: center;">Scoreboard</h3>
                    <p style="margin: 0; font-size: 13px; color: #94a3b8; text-align: center;">Classement global</p>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="challenges-section">
        <div class="section-header">
            <h2>Challenges disponibles</h2>
            <span class="challenge-count"><?php echo count($challenges); ?> challenges au total</span>
        </div>

        <div class="challenges-grid">
            <?php foreach ($challenges as $c): ?>
                <article class="challenge-card">
                    <div class="card-header">
                        <span class="challenge-category"><?php echo Security::e($c['category']); ?></span>
                        <span class="challenge-difficulty"><?php echo Security::e($c['difficulty']); ?></span>
                    </div>

                    <h3 class="challenge-title">
                        <a href="<?php echo $baseUrl; ?>/detail?id=<?php echo (int) $c['id']; ?>">
                            <?php echo Security::e($c['title']); ?>
                        </a>
                    </h3>

                    <p class="challenge-description"><?php 
                        $desc = $c['description'] ?? '';
                        $desc = strlen($desc) > 200 ? substr($desc, 0, 200) . '...' : $desc;
                        echo nl2br(Security::e($desc)); 
                    ?></p>

                    <div class="challenge-meta">
                        <div class="meta-item">Auteur : <?php echo Security::e($c['author_name']); ?></div>
                        <div class="meta-item">Publié le : <?php echo Security::e($c['created_at']); ?></div>
                    </div>

                    <div class="challenge-footer">
                        <?php if (!$c['userHasBought']): ?>
                            <div class="challenge-price"><?php echo number_format($c['price'], 2, ',', ' '); ?> €</div>
                            <form method="post" action="<?php echo $baseUrl; ?>/cart/add" style="display: inline; width: 100%;">
                                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                                <input type="hidden" name="challenge_id" value="<?php echo (int) $c['id']; ?>">
                                <button type="submit" class="btn-start" style="width: 100%; cursor: pointer;">Ajouter au panier</button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo $baseUrl; ?>/detail?id=<?php echo (int) $c['id']; ?>" class="btn-start" style="text-decoration: none; display: inline-block; width: 100%; text-align: center; cursor: pointer;">Accès challenge ✓</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
