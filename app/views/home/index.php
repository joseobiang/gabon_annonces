<section class="hero">
    <h1>Achetez et vendez partout au Gabon</h1>
    <p>Trouvez des bonnes affaires pres de chez vous : vehicules, immobilier, electronique, mode, services... Tout est sur <strong><?= APP_NAME ?></strong>.</p>
    <form action="<?= BASE_URL ?>/annonces" method="get" class="hero-search">
        <input type="text" name="q" placeholder="Que recherchez-vous ?">
        <select name="localisation">
            <option value="">Toutes les provinces</option>
            <?php foreach (PROVINCES_GABON as $p): ?>
                <option value="<?= e($p) ?>"><?= e($p) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Rechercher</button>
    </form>
</section>

<h2 class="section-title">Categories</h2>
<div class="cat-grid">
    <?php foreach ($categories as $c): ?>
        <a href="<?= BASE_URL ?>/annonces?categorie_id=<?= (int)$c['id'] ?>" class="cat-card">
            <div class="cat-icon"><?= icone_categorie($c['icone']) ?></div>
            <div class="cat-name"><?= e($c['nom']) ?></div>
            <div class="cat-count"><?= (int)$c['nb_annonces'] ?> annonce<?= $c['nb_annonces'] > 1 ? 's' : '' ?></div>
        </a>
    <?php endforeach; ?>
</div>

<h2 class="section-title">Annonces recentes</h2>
<?php if (empty($annoncesRecentes)): ?>
    <p class="text-muted">Aucune annonce active.</p>
<?php else: ?>
    <div class="annonces-grid">
        <?php foreach ($annoncesRecentes as $a): ?>
            <a href="<?= BASE_URL ?>/annonces/show/<?= (int)$a['id'] ?>" class="annonce-card">
                <div class="annonce-thumb<?= $a['photo_principale'] ? ' has-photo' : '' ?>"
                     <?php if ($a['photo_principale']): ?>style="background-image:url('<?= e($a['photo_principale']) ?>')"<?php endif; ?>></div>
                <div class="annonce-body">
                    <div class="annonce-titre"><?= e($a['titre']) ?></div>
                    <div class="annonce-prix"><?= format_prix($a['prix']) ?></div>
                    <div class="annonce-meta"><span><?= e($a['localisation']) ?></span><span><?= icone_categorie($a['categorie_icone']) ?></span></div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

