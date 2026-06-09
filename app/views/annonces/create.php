<div class="form-card">
    <h1>Publier une annonce</h1>
    <p class="text-muted">Les annonces sont verifiees par un moderateur avant publication.</p>

    <?php if (!empty($errors)): ?>
        <div class="errors-box"><ul><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <?php if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/annonces/create">
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
                    <option value="">- Choisir -</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= ($old['categorie_id'] == $c['id']) ? 'selected' : '' ?>>
                            <?= icone_categorie($c['icone']) ?> <?= e($c['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <label>Localisation (province) *</label>
            <select name="localisation" required>
                <option value="">- Choisir -</option>
                <?php foreach (PROVINCES_GABON as $p): ?>
                    <option value="<?= e($p) ?>" <?= ($old['localisation'] === $p) ? 'selected' : '' ?>><?= e($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <label>Photos (max 2 Mo chacune)</label>
            <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp">
            <div class="hint">Formats acceptes : JPG, PNG, WEBP.</div>
        </div>

        <button class="btn btn-primary" type="submit">Publier l'annonce</button>
        <a href="<?= BASE_URL ?>/annonces" class="btn btn-ghost" style="color:var(--text-soft);border-color:var(--border);">Annuler</a>
    </form>
</div>

