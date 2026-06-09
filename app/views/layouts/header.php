<?php
$currentUser = $_SESSION['user'] ?? null;
$unread = 0;
if ($currentUser) {
    require_once __DIR__ . '/../../models/Message.php';
    $msgModel = new Message();
    $unread = $msgModel->countUnread($currentUser['id']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titre_page ?? APP_NAME) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <a href="<?= BASE_URL ?>/" class="brand">
            <span class="brand-mark">GA</span>
            <span class="brand-text"><?= APP_NAME ?></span>
        </a>
        <nav class="nav">
            <a href="<?= BASE_URL ?>/annonces">Annonces</a>
            <?php if ($currentUser): ?>
                <a href="<?= BASE_URL ?>/annonces/mine">Mes annonces</a>
                <a href="<?= BASE_URL ?>/favoris">Favoris</a>
                <a href="<?= BASE_URL ?>/messages" class="with-badge">
                    Messages
                    <?php if ($unread > 0): ?><span class="badge"><?= (int)$unread ?></span><?php endif; ?>
                </a>
                <?php if ($currentUser['role'] === 'moderateur'): ?>
                    <a href="<?= BASE_URL ?>/admin" class="link-admin">Moderation</a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>
        <div class="topbar-actions">
            <?php if ($currentUser): ?>
                <span class="hello">Bonjour, <strong><?= e($currentUser['nom']) ?></strong></span>
                <a href="<?= BASE_URL ?>/annonces/create" class="btn btn-primary">+ Deposer</a>
                <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-ghost">Deconnexion</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login" class="btn btn-ghost">Connexion</a>
                <a href="<?= BASE_URL ?>/auth/register" class="btn btn-primary">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<main class="main container">
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="flash flash-success"><?= e($_SESSION['flash_success']) ?></div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="flash flash-error"><?= e($_SESSION['flash_error']) ?></div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>


