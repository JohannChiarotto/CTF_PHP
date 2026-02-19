<?php
?>
<main class="container">
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Rejoignez la communauté <span>Bug Bounty</span></h1>
            <p class="hero-subtitle">
                Créez votre compte gratuitement et commencez à relever des défis de cybersécurité.
            </p>
            <div class="hero-stats">
                <div class="stat-item"><div class="stat-number">Gratuit</div><div class="stat-label">Inscription</div></div>
                <div class="stat-item"><div class="stat-number">∞</div><div class="stat-label">Challenges</div></div>
                <div class="stat-item"><div class="stat-number">24/7</div><div class="stat-label">Accès plateforme</div></div>
            </div>
        </div>
    </section>

    <section class="challenges-section">
        <div class="section-header">
            <h2>Créer mon compte</h2>
            <span class="challenge-count">Tous les champs sont requis sauf la bio</span>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($errors as $e): ?><li><?php echo Security::e($e); ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="register-card">
            <form method="post" action="<?php echo $baseUrl; ?>/register">
                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="text" id="username" name="username" placeholder="Votre pseudo" required
                                value="<?php echo isset($_POST['username']) ? Security::e($_POST['username']) : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" placeholder="votre@email.com" required
                                value="<?php echo isset($_POST['email']) ? Security::e($_POST['email']) : ''; ?>">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Mot de passe <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <span class="password-toggle" onclick="var i=this.previousElementSibling;i.type=i.type==='password'?'text':'password';">👁</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Confirmer mot de passe <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="password_confirm" name="password_confirm" placeholder="••••••••" required>
                            <span class="password-toggle" onclick="var i=this.previousElementSibling;i.type=i.type==='password'?'text':'password';">👁</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <div class="input-wrapper">
                        <textarea id="bio" name="bio" rows="4" placeholder="Présentez-vous en quelques mots..."><?php echo isset($_POST['bio']) ? Security::e($_POST['bio']) : ''; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="skill_level">Niveau <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <select id="skill_level" name="skill_level" required>
                            <option value="Junior" <?php echo (isset($_POST['skill_level']) && $_POST['skill_level'] === 'Junior') ? 'selected' : ''; ?>>Junior</option>
                            <option value="Intermediaire" <?php echo (isset($_POST['skill_level']) && $_POST['skill_level'] === 'Intermediaire') ? 'selected' : ''; ?>>Intermédiaire</option>
                            <option value="Senior" <?php echo (isset($_POST['skill_level']) && $_POST['skill_level'] === 'Senior') ? 'selected' : ''; ?>>Senior</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?php echo $baseUrl; ?>/login" class="btn-secondary">J'ai déjà un compte</a>
                    <button type="submit" class="submit-btn">Créer mon compte</button>
                </div>
            </form>
        </div>
    </section>
</main>
