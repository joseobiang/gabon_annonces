<h1 class="section-title mt-0">Mes favoris</h1>

<?php if (empty($annonces)): ?>
    <p class="text-muted">Vous n'avez aucun favori. Cliquez sur ☆ depuis une annonce pour l'ajouter.</p>
<?php else: ?>
    <?php if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <div class="annonces-grid">
        <?php foreach ($annonces as $a): ?>
            <div class="annonce-card">
                <a href="<?= BASE_URL ?>/annonces/show/<?= (int)$a['id'] ?>">
                    <div class="annonce-thumb<?= $a['photo_principale'] ? ' has-photo' : '' ?>"
                         <?php if ($a['photo_principale']): ?>style="background-image:url('<?= e($a['photo_principale']) ?>')"<?php endif; ?>></div>
                </a>
                <div class="annonce-body">
                    <div class="annonce-titre"><?= e($a['titre']) ?></div>
                    <div class="annonce-prix"><?= format_prix($a['prix']) ?></div>
                    <div class="annonce-meta">
                        <span><?= e($a['localisation']) ?></span>
                        <span><?= icone_categorie($a['categorie_icone']) ?></span>
                    </div>
                    <form action="<?= BASE_URL ?>/favoris/remove/<?= (int)$a['id'] ?>" method="post" class="mt-1">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="redirect" value="favoris">
                        <button class="btn btn-sm btn-danger btn-block" type="submit">★ Retirer</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

