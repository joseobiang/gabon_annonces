<h1 class="section-title mt-0">Tableau de bord - Moderation</h1>

<div class="stats-grid">
    <div class="stat"><div class="n"><?= (int)$stats['en_attente'] ?></div><div class="l">En attente</div></div>
    <div class="stat"><div class="n"><?= (int)$stats['active'] ?></div><div class="l">Actives</div></div>
    <div class="stat"><div class="n"><?= (int)$stats['rejetee'] ?></div><div class="l">Rejetees</div></div>
    <div class="stat"><div class="n"><?= (int)$stats['vendue'] ?></div><div class="l">Vendues</div></div>
</div>

<?php if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>

<table class="table">
    <thead>
        <tr>
            <th>Annonce</th><th>Auteur</th><th>Categorie</th><th>Prix</th><th>Statut</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($annonces)): ?>
            <tr><td colspan="6" class="text-muted">Aucune annonce.</td></tr>
        <?php endif; ?>
        <?php foreach ($annonces as $a): ?>
            <tr>
                <td>
                    <a href="<?= BASE_URL ?>/annonces/show/<?= (int)$a['id'] ?>"><?= e($a['titre']) ?></a>
                    <div class="text-muted" style="font-size:.8rem;"><?= e($a['localisation']) ?> - <?= format_date_fr($a['date_creation']) ?></div>
                </td>
                <td><?= e($a['auteur_nom']) ?></td>
                <td><?= e($a['categorie_nom']) ?></td>
                <td><?= format_prix($a['prix']) ?></td>
                <td><span class="statut statut-<?= e($a['statut']) ?>"><?= e(str_replace('_', ' ', $a['statut'])) ?></span></td>
                <td>
                    <div class="flex">
                        <?php if ($a['statut'] !== 'active'): ?>
                            <form method="post" action="<?= BASE_URL ?>/admin/approve/<?= (int)$a['id'] ?>" class="inline-form">
                                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                                <button class="btn btn-sm btn-success" type="submit">Valider</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($a['statut'] !== 'rejetee'): ?>
                            <form method="post" action="<?= BASE_URL ?>/admin/reject/<?= (int)$a['id'] ?>" class="inline-form">
                                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                                <button class="btn btn-sm btn-danger" type="submit">Rejeter</button>
                            </form>
                        <?php endif; ?>
                        <form method="post" action="<?= BASE_URL ?>/admin/delete/<?= (int)$a['id'] ?>" class="inline-form" data-confirm="Supprimer definitivement ?">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                            <button class="btn btn-sm btn-danger" type="submit">Supprimer</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

