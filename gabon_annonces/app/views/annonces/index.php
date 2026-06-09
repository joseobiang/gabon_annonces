<h1 class="section-title mt-0">Toutes les annonces</h1>

<div class="filters">
    <form method="get" action="<?= BASE_URL ?>/annonces">
        <div>
            <label>Mot-cle</label>
            <input type="text" name="q" value="<?= e($filters['q']) ?>" placeholder="iPhone, Toyota, F3...">
        </div>
        <div>
            <label>Categorie</label>
            <select name="categorie_id">
                <option value="">Toutes</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= ($filters['categorie_id'] == $c['id']) ? 'selected' : '' ?>>
                        <?= e($c['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Localisation</label>
            <select name="localisation">
                <option value="">Toutes</option>
                <?php foreach (PROVINCES_GABON as $p): ?>
                    <option value="<?= e($p) ?>" <?= ($filters['localisation'] === $p) ? 'selected' : '' ?>><?= e($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Prix min (FCFA)</label>
            <input type="number" name="prix_min" min="0" value="<?= e($filters['prix_min']) ?>">
        </div>
        <div>
            <label>Prix max (FCFA)</label>
            <input type="number" name="prix_max" min="0" value="<?= e($filters['prix_max']) ?>">
        </div>
        <div>
            <button class="btn btn-blue btn-block" type="submit">Filtrer</button>
        </div>
    </form>
</div>

<?php if (empty($annonces)): ?>
    <p class="text-muted">Aucune annonce ne correspond a votre recherche.</p>
<?php else: ?>
    <p class="text-muted"><?= count($annonces) ?> annonce(s) trouvee(s)</p>
    <div class="annonces-grid">
        <?php foreach ($annonces as $a): ?>
            <a href="<?= BASE_URL ?>/annonces/show/<?= (int)$a['id'] ?>" class="annonce-card">
                <div class="annonce-thumb<?= $a['photo_principale'] ? ' has-photo' : '' ?>"
                     <?php if ($a['photo_principale']): ?>style="background-image:url('<?= e($a['photo_principale']) ?>')"<?php endif; ?>></div>
                <div class="annonce-body">
                    <div class="annonce-titre"><?= e($a['titre']) ?></div>
                    <div class="annonce-prix"><?= format_prix($a['prix']) ?></div>
                    <div class="annonce-meta">
                        <span><?= e($a['localisation']) ?></span>
                        <span class="annonce-cat"><?= icone_categorie($a['categorie_icone']) ?> <?= e($a['categorie_nom']) ?></span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


