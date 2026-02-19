<?php
?>
<main class="container">
    <section class="hero" style="padding: 120px 0 40px;">
        <div class="hero-content">
            <h1 class="hero-title">Gestion des <span>utilisateurs</span></h1>
            <p class="hero-subtitle"><?php echo count($users); ?> utilisateur(s)</p>
        </div>
    </section>

    <section class="challenges-section">
        <a href="<?php echo $baseUrl; ?>/admin" class="btn-login" style="display: inline-block; margin-bottom: 25px; text-decoration: none;">← Retour admin</a>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Banni</th>
                        <th>Score</th>
                        <th>Solde</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?php echo (int) $u['id']; ?></td>
                        <td><?php echo Security::e($u['username']); ?></td>
                        <td><?php echo Security::e($u['email']); ?></td>
                        <td><?php echo Security::e($u['role']); ?></td>
                        <td class="<?php echo $u['is_banned'] ? 'badge-banned' : 'badge-ok'; ?>"><?php echo $u['is_banned'] ? 'Oui' : 'Non'; ?></td>
                        <td><?php echo (int) $u['score']; ?></td>
                        <td><?php echo number_format($u['balance'], 2, ',', ' '); ?></td>
                        <td class="actions-cell">
                            <form method="post" action="<?php echo $baseUrl; ?>/admin/user/role">
                                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                                <input type="hidden" name="user_id" value="<?php echo (int) $u['id']; ?>">
                                <select name="role" onchange="this.form.submit()">
                                    <option value="user" <?php echo $u['role'] === 'user' ? 'selected' : ''; ?>>user</option>
                                    <option value="creator" <?php echo $u['role'] === 'creator' ? 'selected' : ''; ?>>creator</option>
                                    <option value="admin" <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>admin</option>
                                </select>
                            </form>
                            <form method="post" action="<?php echo $baseUrl; ?>/admin/user/ban">
                                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                                <input type="hidden" name="user_id" value="<?php echo (int) $u['id']; ?>">
                                <label><input type="checkbox" name="ban" <?php echo $u['is_banned'] ? 'checked' : ''; ?> onchange="this.form.submit()"> Bannir</label>
                            </form>
                            <form method="post" action="<?php echo $baseUrl; ?>/admin/user/reset" onsubmit="return confirm('Remettre à zéro score et solde ?');">
                                <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                                <input type="hidden" name="user_id" value="<?php echo (int) $u['id']; ?>">
                                <button type="submit" class="btn-danger">Reset</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
