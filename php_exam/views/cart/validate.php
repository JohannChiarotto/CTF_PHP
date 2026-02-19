<?php
?>
<main class="container">
    <section class="hero" style="padding: 120px 0 40px;">
        <div class="hero-content">
            <h1 class="hero-title">Validation du <span>panier</span></h1>
            <p class="hero-subtitle">Confirmez votre commande</p>
        </div>
    </section>

    <section class="challenges-section">
        <?php if (!empty($success)): ?>
            <div class="challenge-card" style="max-width: 600px; margin: 0 auto; text-align: center;">
                <h2 style="color: #22c55e; margin-bottom: 15px;">Commande validée</h2>
                <p>Facture n° <?php echo (int) $invoiceId; ?></p>
                <a href="<?php echo $baseUrl; ?>/" class="btn-start" style="display: inline-block; text-decoration: none; margin-top: 20px;">Retour à l'accueil</a>
            </div>
        <?php else: ?>
            <?php if (!empty($error)): ?>
                <p style="color: #f87171; margin-bottom: 20px;"><?php echo Security::e($error); ?></p>
            <?php endif; ?>

            <div class="register-card" style="max-width: 500px; margin: 0 auto;">
                <p style="font-size: 18px; margin-bottom: 25px;">Total : <strong style="color: #00ffff;"><?php echo number_format($total, 2, ',', ' '); ?> €</strong></p>

                <form method="post" action="<?php echo $baseUrl; ?>/cart/validate">
                    <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">

                    <div class="form-group">
                        <label for="billing_address">Adresse de facturation *</label>
                        <div class="input-wrapper">
                            <input type="text" id="billing_address" name="billing_address" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="billing_city">Ville *</label>
                            <div class="input-wrapper">
                                <input type="text" id="billing_city" name="billing_city" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="billing_zip">Code postal *</label>
                            <div class="input-wrapper">
                                <input type="text" id="billing_zip" name="billing_zip" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Confirmer l'achat</button>
                </form>
            </div>
        <?php endif; ?>
    </section>
</main>
