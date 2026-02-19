<?php
?>
<main class="container">
    <section class="scoreboard-hero">
        <h1>🏆 Classement des pentesters</h1>
        <p>Mesurez-vous aux meilleurs hackers de la plateforme</p>
    </section>

    <?php if (!empty($top)): ?>

    <?php if (count($top) >= 3): ?>
    <section class="podium-section">
        <div class="podium">
            <?php
            $podium_order = [];
            if (isset($top[1])) $podium_order[] = ['user' => $top[1], 'rank' => 2];
            if (isset($top[0])) $podium_order[] = ['user' => $top[0], 'rank' => 1];
            if (isset($top[2])) $podium_order[] = ['user' => $top[2], 'rank' => 3];
            ?>
            <?php foreach ($podium_order as $entry):
                $u = $entry['user'];
                $r = $entry['rank'];
                $initial = mb_strtoupper(mb_substr(Security::e($u['username']), 0, 1));
            ?>
            <div class="podium-item">
                <div class="podium-avatar"><?php echo $initial; ?></div>
                <div class="podium-name"><?php echo Security::e($u['username']); ?></div>
                <div class="podium-score"><?php echo number_format((int)$u['score'], 0, ',', ' '); ?> pts</div>
                <div class="podium-level"><?php echo Security::e($u['skill_level']); ?></div>
                <div class="podium-block"><?php echo $r; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="scoreboard-section">
        <div class="scoreboard-table-wrap">
            <table class="scoreboard-table">
                <thead>
                    <tr>
                        <th class="col-rank">#</th>
                        <th>Pentester</th>
                        <th>Niveau</th>
                        <th class="col-score">Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1; foreach ($top as $user):
                        $rowClass   = match($rank) { 1 => 'top-1', 2 => 'top-2', 3 => 'top-3', default => '' };
                        $badgeClass = match($rank) { 1 => 'gold',  2 => 'silver', 3 => 'bronze', default => 'default' };
                        $skillClass = strtolower(preg_replace('/\s+/', '-', Security::e($user['skill_level'])));
                        $initial    = mb_strtoupper(mb_substr(Security::e($user['username']), 0, 1));
                    ?>
                    <tr class="<?php echo $rowClass; ?>">
                        <td class="col-rank">
                            <span class="rank-badge <?php echo $badgeClass; ?>"><?php echo $rank; ?></span>
                        </td>
                        <td>
                            <div class="username-cell">
                                <div class="user-initials"><?php echo $initial; ?></div>
                                <span class="username-text"><?php echo Security::e($user['username']); ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="skill-pill <?php echo htmlspecialchars($skillClass); ?>">
                                <?php echo Security::e($user['skill_level']); ?>
                            </span>
                        </td>
                        <td class="col-score">
                            <span class="score-value"><?php echo number_format((int)$user['score'], 0, ',', ' '); ?></span>
                        </td>
                    </tr>
                    <?php $rank++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php else: ?>
    <section class="scoreboard-section">
        <div class="scoreboard-table-wrap">
            <div class="empty-state">
                <span style="font-size:48px">🎯</span>
                <p>Aucun pentester n'a encore marqué de points.<br>Soyez le premier !</p>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>
