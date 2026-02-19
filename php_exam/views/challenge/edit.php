<?php
?>
<main class="container">
    <section class="hero" style="padding: 120px 0 40px;">
        <div class="hero-content">
            <h1 class="hero-title">Modifier le <span>challenge</span></h1>
            <p class="hero-subtitle"><?php echo Security::e($challenge['title']); ?></p>
        </div>
    </section>

    <section class="challenges-section form-page">
        <div class="section-header">
            <h2>Édition</h2>
            <a href="<?php echo $baseUrl; ?>/detail?id=<?php echo (int) $challenge['id']; ?>" class="btn-login" style="text-decoration: none;">← Retour</a>
        </div>

        <div class="form-card">
            <form method="post" action="<?php echo $baseUrl; ?>/edit">
                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                <input type="hidden" name="id" value="<?php echo (int) $challenge['id']; ?>">

                <div class="form-row">
                    <div class="form-group full-width">
                        <label for="title">Titre *</label>
                        <input type="text" id="title" name="title" value="<?php echo Security::e($challenge['title']); ?>" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" required><?php echo Security::e($challenge['description']); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Catégorie *</label>
                        <input type="text" id="category" name="category" value="<?php echo Security::e($challenge['category']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="difficulty">Difficulté *</label>
                        <select id="difficulty" name="difficulty">
                            <?php foreach (['Noob','Mid','Ardu','Fou','CybersecurityTitle'] as $d): ?>
                            <option value="<?php echo $d; ?>" <?php echo $challenge['difficulty'] === $d ? 'selected' : ''; ?>><?php echo $d; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Prix *</label>
                        <input type="number" id="price" name="price" step="0.01" value="<?php echo Security::e($challenge['price']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="access_url">URL d'accès *</label>
                        <input type="text" id="access_url" name="access_url" value="<?php echo Security::e($challenge['access_url']); ?>" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="image_url">Image (URL)</label>
                    <input type="text" id="image_url" name="image_url" value="<?php echo Security::e($challenge['image_url'] ?? ''); ?>">
                </div>

                <div class="form-group full-width">
                    <label for="flag">Nouveau flag (laisser vide pour ne pas changer)</label>
                    <input type="text" id="flag" name="flag">
                </div>

                <div class="form-group full-width">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="is_active" <?php echo $challenge['is_active'] ? 'checked' : ''; ?>>
                        Challenge actif
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-start">Enregistrer</button>
                    <form method="post" action="<?php echo $baseUrl; ?>/delete-challenge" onsubmit="return confirm('Supprimer ce challenge ?');" style="display: inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                        <input type="hidden" name="id" value="<?php echo (int) $challenge['id']; ?>">
                        <button type="submit" class="btn-delete">Supprimer</button>
                    </form>
                </div>
            </form>
        </div>
    </section>
</main>
