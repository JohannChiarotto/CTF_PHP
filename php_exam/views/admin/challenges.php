<?php
?>
<main class="container">
    <section class="hero" style="padding: 120px 0 40px;">
        <div class="hero-content">
            <h1 class="hero-title">Gestion des <span>challenges</span></h1>
            <p class="hero-subtitle"><?php echo count($challenges); ?> challenge(s)</p>
        </div>
    </section>

    <section class="challenges-section">
        <a href="<?php echo $baseUrl; ?>/admin" class="btn-login" style="display: inline-block; margin-bottom: 25px; text-decoration: none;">← Retour admin</a>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Actif</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($challenges as $c): ?>
                    <tr>
                        <td><?php echo (int) $c['id']; ?></td>
                        <td><?php echo Security::e($c['title']); ?></td>
                        <td><?php echo Security::e($c['author_name']); ?></td>
                        <td><?php echo $c['is_active'] ? 'Oui' : 'Non'; ?></td>
                        <td class="actions-cell">
                            <a href="<?php echo $baseUrl; ?>/detail?id=<?php echo (int) $c['id']; ?>" class="link-btn">Voir</a>
                            <form method="post" action="<?php echo $baseUrl; ?>/admin/challenge/toggle" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                                <input type="hidden" name="challenge_id" value="<?php echo (int) $c['id']; ?>">
                                <button type="submit"><?php echo $c['is_active'] ? 'Désactiver' : 'Activer'; ?></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
