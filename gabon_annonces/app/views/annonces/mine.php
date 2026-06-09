<div class="flex-between">
    <h1 class="section-title mt-0">Mes annonces</h1>
    <a href="<?= BASE_URL ?>/annonces/create" class="btn btn-primary">+ Nouvelle annonce</a>
</div>

<?php if (empty($annonces)): ?>
    <p class="text-muted">Vous n'avez encore publie aucune annonce.</p>
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
                        <span class="statut statut-<?= e($a['statut']) ?>"><?= e(str_replace('_', ' ', $a['statut'])) ?></span>
                        <span><?= e($a['localisation']) ?></span>
                    </div>
                    <div class="flex mt-1">
                        <a href="<?= BASE_URL ?>/annonces/edit/<?= (int)$a['id'] ?>" class="btn btn-sm btn-blue">Modifier</a>
                        <form action="<?= BASE_URL ?>/annonces/delete/<?= (int)$a['id'] ?>" method="post" class="inline-form" data-confirm="Supprimer cette annonce ?">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                            <button class="btn btn-sm btn-danger" type="submit">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

