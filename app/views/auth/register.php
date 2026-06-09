<div class="form-card">
    <h1>Creer un compte</h1>
    <p class="text-muted">Deja inscrit ? <a href="<?= BASE_URL ?>/auth/login">Connectez-vous</a>.</p>

    <?php if (!empty($errors)): ?>
        <div class="errors-box"><ul><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <?php if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <form method="post" action="<?= BASE_URL ?>/auth/register">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
        <div class="form-row">
            <label>Nom complet *</label>
            <input type="text" name="nom" value="<?= e($old['nom']) ?>" required>
        </div>
        <div class="form-row">
            <label>Email *</label>
            <input type="email" name="email" value="<?= e($old['email']) ?>" required>
        </div>
        <div class="form-row">
            <label>Telephone</label>
            <input type="tel" name="telephone" value="<?= e($old['telephone']) ?>" placeholder="+241 ...">
        </div>
        <div class="form-row">
            <label>Je suis... *</label>
            <select name="role">
                <option value="annonceur" <?= $old['role'] === 'annonceur' ? 'selected' : '' ?>>Annonceur (je veux vendre)</option>
                <option value="acheteur"  <?= $old['role'] === 'acheteur'  ? 'selected' : '' ?>>Acheteur (je veux acheter)</option>
            </select>
        </div>
        <div class="form-grid-2">
            <div class="form-row">
                <label>Mot de passe * (6 caracteres min.)</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="form-row">
                <label>Confirmer *</label>
                <input type="password" name="password_confirm" required minlength="6">
            </div>
        </div>
        <button class="btn btn-primary btn-block" type="submit">Creer mon compte</button>
    </form>
</div>

