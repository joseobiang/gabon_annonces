<?php
class Favoris extends Model {
    public function allForUser($userId) {
        return $this->fetchAll(
            "SELECT a.*, c.nom AS categorie_nom, c.icone AS categorie_icone,
                    (SELECT url FROM annonce_photos p WHERE p.annonce_id = a.id ORDER BY p.id ASC LIMIT 1) AS photo_principale
             FROM favoris f
             JOIN annonces a ON a.id = f.annonce_id
             JOIN categories c ON c.id = a.categorie_id
             WHERE f.utilisateur_id = ? AND a.statut = 'active'
             ORDER BY f.date_ajout DESC",
            [$userId]
        );
    }

    public function isFavori($userId, $annonceId) {
        $row = $this->fetchOne(
            'SELECT id FROM favoris WHERE utilisateur_id = ? AND annonce_id = ?',
            [$userId, $annonceId]
        );
        return (bool)$row;
    }

    public function add($userId, $annonceId) {
        $this->query(
            'INSERT IGNORE INTO favoris (utilisateur_id, annonce_id) VALUES (?, ?)',
            [$userId, $annonceId]
        );
    }

    public function remove($userId, $annonceId) {
        $this->query(
            'DELETE FROM favoris WHERE utilisateur_id = ? AND annonce_id = ?',
            [$userId, $annonceId]
        );
    }
}

