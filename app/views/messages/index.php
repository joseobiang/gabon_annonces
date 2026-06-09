<h1 class="section-title mt-0">Messagerie</h1>

<?php if (empty($conversations)): ?>
    <p class="text-muted">Aucune conversation. Contactez un vendeur depuis une annonce pour demarrer.</p>
<?php else: ?>
    <div class="conv-list">
        <?php foreach ($conversations as $c): ?>
            <a href="<?= BASE_URL ?>/messages/show/<?= (int)$c['annonce_id'] ?>/<?= (int)$c['interlocuteur_id'] ?>" class="conv-item">
                <div class="top">
                    <span class="who">
                        <?= e($c['interlocuteur_nom']) ?>
                        <?php if ($c['non_lus'] > 0): ?><span class="badge"><?= (int)$c['non_lus'] ?></span><?php endif; ?>
                    </span>
                    <span class="when"><?= format_date_fr($c['dernier_message']) ?></span>
                </div>
                <div class="annonce">Annonce : <?= e($c['annonce_titre']) ?></div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

