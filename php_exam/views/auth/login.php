<?php
?>
<main class="container">
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Connexion <span>Bug Bounty</span></h1>
            <p class="hero-subtitle">Accédez à votre compte et relevez des défis.</p>
        </div>
    </section>

    <section class="challenges-section">
        <div class="login-card">
            <?php if (!empty($error)): ?>
                <p class="error-msg"><?php echo Security::e($error); ?></p>
            <?php endif; ?>

            <form method="post" action="<?php echo $baseUrl; ?>/login">
                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">

                <div class="form-group">
                    <label for="identifier">Email ou nom d'utilisateur</label>
                    <div class="input-wrapper">
                        <input type="text" id="identifier" name="identifier" placeholder="Votre identifiant" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <span class="password-toggle" onclick="var i=this.previousElementSibling;i.type=i.type==='password'?'text':'password';">👁</span>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Se connecter</button>

                <p class="register-link">
                    Pas encore de compte ? <a href="<?php echo $baseUrl; ?>/register">Créer un compte</a>
                </p>
            </form>
        </div>
    </section>
</main>
