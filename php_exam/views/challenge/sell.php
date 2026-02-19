<?php
?>
<main class="container">
    <section class="hero" style="padding: 120px 0 40px;">
        <div class="hero-content">
            <h1 class="hero-title">Créer un <span>challenge</span></h1>
            <p class="hero-subtitle">Publiez votre défi sur la plateforme</p>
        </div>
    </section>

    <section class="challenges-section form-page">
        <div class="section-header">
            <h2>Nouveau challenge</h2>
        </div>

        <?php if (!empty($error)): ?>
            <p class="error-msg" style="margin-bottom: 20px;"><?php echo Security::e($error); ?></p>
        <?php endif; ?>

        <div class="form-card">
            <form method="post" action="<?php echo $baseUrl; ?>/sell">
                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="title">Titre *</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Catégorie *</label>
                        <input type="text" id="category" name="category" required>
                    </div>
                    <div class="form-group">
                        <label for="difficulty">Difficulté *</label>
                        <select id="difficulty" name="difficulty">
                            <option value="Noob">Noob</option>
                            <option value="Mid">Mid</option>
                            <option value="Ardu">Ca commence à être ardu</option>
                            <option value="Fou">Je vais devenir Fou</option>
                            <option value="CybersecurityTitle">Give Me CYBERSECURITY title</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Prix *</label>
                        <input type="number" id="price" name="price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="access_url">URL d'accès *</label>
                        <input type="text" id="access_url" name="access_url" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="image_url">Image (URL)</label>
                    <input type="text" id="image_url" name="image_url">
                </div>

                <div class="form-group full-width">
                    <label for="flag">Flag correct *</label>
                    <input type="text" id="flag" name="flag" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-start">Créer le challenge</button>
                </div>
            </form>
        </div>
    </section>
</main>
