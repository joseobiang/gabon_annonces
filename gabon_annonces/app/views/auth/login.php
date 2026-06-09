<div class="form-card">
    <h1>Connexion</h1>
    <p class="text-muted">Pas encore de compte ? <a href="<?= BASE_URL ?>/auth/register">Inscrivez-vous</a>.</p>

    <?php if (!empty($errors)): ?>
        <div class="errors-box"><ul><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <?php if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <form method="post" action="<?= BASE_URL ?>/auth/login">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
        <div class="form-row">
            <label>Email</label>
            <input type="email" name="email" value="<?= e($email) ?>" required autofocus>
        </div>
        <div class="form-row">
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <button class="btn btn-primary btn-block" type="submit">Se connecter</button>
    </form>

    <div class="mt-3 text-muted" style="font-size:.85rem;">
        <strong>Comptes de demo :</strong><br>
        Moderateur : admin@gabon-annonces.ga / admin123<br>
        Utilisateur : jean@example.com / user123
    </div>
</div>

