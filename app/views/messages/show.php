<?php $myId = $_SESSION['user']['id']; ?>
<div class="flex-between">
    <h1 class="section-title mt-0">Conversation avec <?= e($other['nom']) ?></h1>
    <a href="<?= BASE_URL ?>/messages" class="btn btn-sm btn-ghost" style="color:var(--text-soft);border-color:var(--border);"><- Toutes les conversations</a>
</div>

<div class="sidebar-card mb-2">
    <strong>Annonce :</strong>
    <a href="<?= BASE_URL ?>/annonces/show/<?= (int)$annonce['id'] ?>"><?= e($annonce['titre']) ?></a>
    - <?= format_prix($annonce['prix']) ?>
</div>

<div class="chat">
    <div class="chat-messages">
        <?php if (empty($messages)): ?>
            <p class="text-muted">Aucun message. Demarrez la conversation ci-dessous.</p>
        <?php endif; ?>
        <?php foreach ($messages as $m): ?>
            <div class="msg <?= $m['expediteur_id'] == $myId ? 'me' : 'them' ?>">
                <?= nl2br(e($m['contenu'])) ?>
                <span class="meta"><?= format_date_fr($m['date_envoi']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <form method="post" class="chat-form" action="<?= BASE_URL ?>/messages/show/<?= (int)$annonce['id'] ?>/<?= (int)$other['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
        <textarea name="contenu" rows="2" placeholder="Votre message..." required></textarea>
        <button class="btn btn-blue" type="submit">Envoyer</button>
    </form>
</div>

