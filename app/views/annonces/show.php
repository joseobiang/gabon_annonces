<?php $u = $_SESSION['user'] ?? null; ?>
<div class="annonce-detail">
    <div>
        <div class="annonce-gallery">
            <?php $first = $photos[0]['url'] ?? ''; ?>
            <div id="main-photo" class="main-photo"
                 style="<?= $first ? "background-image:url('" . e($first) . "')" : '' ?>"></div>
            <?php if (count($photos) > 1): ?>
                <div class="thumbs">
                    <?php foreach ($photos as $i => $p): ?>
                        <img src="<?= e($p['url']) ?>" alt="" class="gallery-thumb <?= $i === 0 ? 'active' : '' ?>" data-url="<?= e($p['url']) ?>">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="annonce-info mt-2">
            <span class="statut statut-<?= e($annonce['statut']) ?>"><?= e(str_replace('_', ' ', $annonce['statut'])) ?></span>
            <h1><?= e($annonce['titre']) ?></h1>
            <div class="text-muted">
                <?= icone_categorie($annonce['categorie_icone']) ?> <?= e($annonce['categorie_nom']) ?>
                - <?= e($annonce['localisation']) ?>
                - Publiee le <?= format_date_fr($annonce['date_creation']) ?>
            </div>
            <div class="prix"><?= format_prix($annonce['prix']) ?></div>
            <div class="description"><?= nl2br(e($annonce['description'])) ?></div>
        </div>
    </div>

    <aside>
        <div class="sidebar-card">
            <h3>Vendeur</h3>
            <p class="mt-0 mb-1"><strong><?= e($annonce['auteur_nom']) ?></strong></p>
            <?php if (!empty($annonce['auteur_telephone'])): ?>
                <p class="mt-0 mb-1">Tel: <?= e($annonce['auteur_telephone']) ?></p>
            <?php endif; ?>
            <p class="mt-0 text-muted">Email: <?= e($annonce['auteur_email']) ?></p>
        </div>

        <?php if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>

        <?php if (!$u): ?>
            <div class="sidebar-card">
                <p class="mt-0">Connectez-vous pour contacter le vendeur ou ajouter aux favoris.</p>
                <a href="<?= BASE_URL ?>/auth/login" class="btn btn-primary btn-block">Se connecter</a>
            </div>
        <?php elseif ($u['id'] != $annonce['auteur_id']): ?>
            <div class="sidebar-card">
                <h3>Contacter le vendeur</h3>
                <form action="<?= BASE_URL ?>/messages/start/<?= (int)$annonce['id'] ?>" method="post">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                    <textarea name="contenu" rows="3" placeholder="Bonjour, est-ce toujours disponible ?" required style="width:100%;padding:10px;border-radius:8px;border:1px solid var(--border);"></textarea>
                    <button class="btn btn-blue btn-block mt-1" type="submit">Envoyer un message</button>
                </form>
            </div>

            <div class="sidebar-card">
                <?php if ($isFavori): ?>
                    <form action="<?= BASE_URL ?>/favoris/remove/<?= (int)$annonce['id'] ?>" method="post">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                        <button class="btn btn-danger btn-block" type="submit">★ Retirer des favoris</button>
                    </form>
                <?php else: ?>
                    <form action="<?= BASE_URL ?>/favoris/add/<?= (int)$annonce['id'] ?>" method="post">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                        <button class="btn btn-success btn-block" type="submit">☆ Ajouter aux favoris</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="sidebar-card">
                <h3>C'est votre annonce</h3>
                <a href="<?= BASE_URL ?>/annonces/edit/<?= (int)$annonce['id'] ?>" class="btn btn-blue btn-block mb-1">Modifier</a>
                <?php if ($annonce['statut'] === 'active'): ?>
                    <form action="<?= BASE_URL ?>/annonces/vendre/<?= (int)$annonce['id'] ?>" method="post" data-confirm="Confirmer comme vendue ?">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                        <button class="btn btn-success btn-block mb-1" type="submit">Marquer vendue</button>
                    </form>
                <?php endif; ?>
                <form action="<?= BASE_URL ?>/annonces/delete/<?= (int)$annonce['id'] ?>" method="post" data-confirm="Supprimer definitivement ?">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                    <button class="btn btn-danger btn-block" type="submit">Supprimer</button>
                </form>
            </div>
        <?php endif; ?>
    </aside>
</div>

