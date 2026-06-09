<div class="form-card">
    <h1>Modifier l'annonce</h1>

    <?php if (!empty($errors)): ?>
        <div class="errors-box"><ul><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <?php if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/annonces/edit/<?= (int)$annonce['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <div class="form-row">
            <label>Titre *</label>
            <input type="text" name="titre" value="<?= e($old['titre']) ?>" required minlength="4" maxlength="200">
        </div>
        <div class="form-row">
            <label>Description *</label>
            <textarea name="description" required minlength="10"><?= e($old['description']) ?></textarea>
        </div>
        <div class="form-grid-2">
            <div class="form-row">
                <label>Prix (FCFA) *</label>
                <input type="number" name="prix" value="<?= e($old['prix']) ?>" min="0" required>
            </div>
            <div class="form-row">
                <label>Categorie *</label>
                <select name="categorie_id" required>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= ($old['categorie_id'] == $c['id']) ? 'selected' : '' ?>><?= e($c['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <label>Localisation *</label>
            <select name="localisation" required>
                <?php foreach (PROVINCES_GABON as $p): ?>
                    <option value="<?= e($p) ?>" <?= ($old['localisation'] === $p) ? 'selected' : '' ?>><?= e($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!empty($photos)): ?>
            <div class="form-row">
                <label>Photos actuelles</label>
                <div class="photos-actuelles">
                    <?php foreach ($photos as $p): ?>
                        <img src="<?= e($p['url']) ?>" alt="">
                    <?php endforeach; ?>
                </div>
                <div class="hint">Les nouvelles photos s'ajouteront a celles-ci.</div>
            </div>
        <?php endif; ?>

        <div class="form-row">
            <label>Ajouter des photos</label>
            <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp">
        </div>

        <button class="btn btn-primary" type="submit">Enregistrer</button>
        <a href="<?= BASE_URL ?>/annonces/show/<?= (int)$annonce['id'] ?>" class="btn btn-ghost" style="color:var(--text-soft);border-color:var(--border);">Annuler</a>
    </form>
</div>

