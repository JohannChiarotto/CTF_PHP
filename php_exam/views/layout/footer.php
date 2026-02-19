<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Bug Bounty CTF</h4>
                <p>Plateforme de challenges cybersécurité. Entraînez-vous, montez dans le classement et devenez un meilleur pentester.</p>
            </div>
            <div class="footer-section">
                <h4>Liens</h4>
                <ul>
                    <li><a href="<?php echo $baseUrl; ?>/">Accueil</a></li>
                    <li><a href="<?php echo $baseUrl; ?>/scoreboard">Scoreboard</a></li>
                    <li><a href="<?php echo $baseUrl; ?>/register">Inscription</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Compte</h4>
                <ul>
                    <?php if (Auth::check()): ?>
                        <li><a href="<?php echo $baseUrl; ?>/account">Mon compte</a></li>
                        <li><a href="<?php echo $baseUrl; ?>/cart">Panier</a></li>
                        <li><a href="<?php echo $baseUrl; ?>/logout">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo $baseUrl; ?>/login">Connexion</a></li>
                        <li><a href="<?php echo $baseUrl; ?>/register">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">Bug Bounty CTF - Projet PHP</div>
    </div>
</footer>
</body>
</html>
