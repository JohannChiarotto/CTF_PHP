<?php
?>
<main class="container">
    <section class="hero" style="padding: 120px 0 40px;">
        <div class="hero-content">
            <h1 class="hero-title">Mon <span>compte</span></h1>
            <p class="hero-subtitle"><?php echo Security::e($user['username']); ?></p>
        </div>
    </section>

    <section class="challenges-section">
        <div class="account-grid">
            <div class="account-card">
                <h2>Informations</h2>
                <div class="profile-stat"><span>Email</span><strong><?php echo Security::e($user['email']); ?></strong></div>
                <div class="profile-stat"><span>Bio</span><strong><?php echo nl2br(Security::e($user['bio'] ?? '-')); ?></strong></div>
                <div class="profile-stat"><span>Niveau</span><span class="value"><?php echo Security::e($user['skill_level']); ?></span></div>
                <div class="profile-stat"><span>Solde</span><span class="value"><?php echo number_format($user['balance'], 2, ',', ' '); ?> €</span></div>
                <div class="profile-stat"><span>Score</span><span class="value"><?php echo (int) $user['score']; ?> pts</span></div>
            </div>

            <div class="account-card">
                <h2>Modifier mes informations</h2>
                <form method="post" action="<?php echo $baseUrl; ?>/account/update">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo Security::e($user['email']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Bio</label>
                        <textarea name="bio" rows="3"><?php echo Security::e($user['bio'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Niveau</label>
                        <select name="skill_level">
                            <option value="Junior" <?php echo $user['skill_level'] === 'Junior' ? 'selected' : ''; ?>>Junior</option>
                            <option value="Intermediaire" <?php echo $user['skill_level'] === 'Intermediaire' ? 'selected' : ''; ?>>Intermédiaire</option>
                            <option value="Senior" <?php echo $user['skill_level'] === 'Senior' ? 'selected' : ''; ?>>Senior</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Nouveau mot de passe (optionnel)</label>
                        <input type="password" name="password" style="width:100%;padding:12px;background:rgba(30,41,59,0.6);border:2px solid rgba(0,255,255,0.2);border-radius:8px;color:#fff;">
                    </div>
                    <button type="submit" class="btn-start">Mettre à jour</button>
                </form>

                <h3>Ajouter des crédits</h3>
                <form method="post" action="<?php echo $baseUrl; ?>/account/add-balance" style="display: flex; gap: 10px; align-items: center;">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                    <input type="number" name="amount" step="0.01" min="0" placeholder="Montant" style="flex:1;padding:12px;background:rgba(30,41,59,0.6);border:2px solid rgba(0,255,255,0.2);border-radius:8px;color:#fff;">
                    <div class="form-group">
                        <button type="submit" class="btn-start">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="account-card" style="margin-top: 30px;">
            <h2>Challenges créés</h2>
            <?php foreach ($createdChallenges as $c): ?>
                <div class="challenge-list-item">
                    <a href="<?php echo $baseUrl; ?>/detail?id=<?php echo (int) $c['id']; ?>"><?php echo Security::e($c['title']); ?></a>
                    <a href="<?php echo $baseUrl; ?>/edit?id=<?php echo (int) $c['id']; ?>" class="link-btn" style="font-size:13px;">Modifier</a>
                </div>
            <?php endforeach; ?>
            <?php if (empty($createdChallenges)): ?><p style="color:#94a3b8;">Aucun challenge créé.</p><?php endif; ?>
        </div>

        <div class="account-card" style="margin-top: 20px;">
            <h2>Challenges achetés</h2>
            <?php foreach ($boughtChallenges as $c): ?>
                <div class="challenge-list-item">
                    <a href="<?php echo $baseUrl; ?>/detail?id=<?php echo (int) $c['id']; ?>"><?php echo Security::e($c['title']); ?></a>
                    <span class="<?php echo $c['solved'] ? 'solved' : 'not-solved'; ?>"><?php echo $c['solved'] ? '✓ Résolu' : '—'; ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (empty($boughtChallenges)): ?><p style="color:#94a3b8;">Aucun challenge acheté.</p><?php endif; ?>
        </div>

        <div class="account-card" style="margin-top: 20px;">
            <h2>Factures</h2>
            <?php foreach ($invoices as $inv): ?>
                <div class="invoice-item">
                    <a href="<?php echo $baseUrl; ?>/invoice?id=<?php echo (int) $inv['id']; ?>">Facture #<?php echo (int) $inv['id']; ?></a>
                    <span class="amount"><?php echo number_format($inv['amount'], 2, ',', ' '); ?> €</span>
                    <a href="<?php echo $baseUrl; ?>/invoice?id=<?php echo (int) $inv['id']; ?>&download=1" class="link-btn" style="font-size:13px;margin-left:10px;">Télécharger</a>
                </div>
            <?php endforeach; ?>
            <?php if (empty($invoices)): ?><p style="color:#94a3b8;">Aucune facture.</p><?php endif; ?>
        </div>
    </section>
</main>
