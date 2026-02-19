<?php
?>
<main class="container">
    <section class="hero" style="padding: 120px 0 40px;">
        <div class="hero-content">
            <h1 class="hero-title">Mon <span>panier</span></h1>
            <p class="hero-subtitle">Gérez vos challenges avant validation</p>
        </div>
    </section>

    <section class="challenges-section">
        <?php if (empty($items)): ?>
            <div class="empty-state" style="text-align: center; padding: 80px 40px; background: rgba(15,23,42,0.8); border: 1px solid rgba(0,255,255,0.2); border-radius: 15px;">
                <span style="font-size: 64px;">🛒</span>
                <h2 style="margin: 20px 0;">Votre panier est vide</h2>
                <p style="color: #94a3b8; margin-bottom: 25px;">Découvrez nos challenges et ajoutez-en à votre panier.</p>
                <a href="<?php echo $baseUrl; ?>/" class="btn-start" style="display: inline-block; text-decoration: none;">Voir les challenges</a>
            </div>
        <?php else: ?>
            <div class="section-header">
                <h2>Articles dans votre panier</h2>
                <span class="challenge-count"><?php echo count($items); ?> article(s)</span>
            </div>

            <div class="scoreboard-table-wrap">
                <table class="scoreboard-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo Security::e($item['title']); ?></td>
                            <td><?php echo number_format($item['price'], 2, ',', ' '); ?> €</td>
                            <td>
                                <form method="post" action="<?php echo $baseUrl; ?>/cart/update" style="display: inline-flex; gap: 5px; align-items: center;">
                                    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                                    <input type="hidden" name="challenge_id" value="<?php echo (int) $item['challenge_id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo (int) $item['quantity']; ?>" min="1" style="width: 70px; padding: 8px; background: rgba(30,41,59,0.6); border: 2px solid rgba(0,255,255,0.2); border-radius: 6px; color: #fff;">
                                    <button type="submit" class="btn-start" style="padding: 8px 16px;">Mettre à jour</button>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="<?php echo $baseUrl; ?>/cart/remove" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                                    <input type="hidden" name="challenge_id" value="<?php echo (int) $item['challenge_id']; ?>">
                                    <button type="submit" style="padding: 8px 16px; background: rgba(239,68,68,0.2); border: 1px solid rgba(239,68,68,0.3); color: #f87171; border-radius: 8px; cursor: pointer;">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <p style="font-size: 20px; font-weight: 700; color: #00ffff;">Total : <?php echo number_format($total, 2, ',', ' '); ?> €</p>
                <a href="<?php echo $baseUrl; ?>/cart/validate" class="btn-start" style="display: inline-block; text-decoration: none;">Valider le panier</a>
            </div>
        <?php endif; ?>
    </section>
</main>
