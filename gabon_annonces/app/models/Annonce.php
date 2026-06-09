<?php
class Annonce extends Model {
    public function search($filters = [], $limit = 30, $offset = 0) {
        $sql = "SELECT a.*, c.nom AS categorie_nom, c.icone AS categorie_icone, u.nom AS auteur_nom,
                       (SELECT url FROM annonce_photos p WHERE p.annonce_id = a.id ORDER BY p.id ASC LIMIT 1) AS photo_principale
                FROM annonces a
                JOIN categories c ON c.id = a.categorie_id
                JOIN users u ON u.id = a.auteur_id
                WHERE 1=1";
        $params = [];
        $statut = $filters['statut'] ?? 'active';
        $sql .= ' AND a.statut = ?'; $params[] = $statut;
        if (!empty($filters['categorie_id'])) { $sql .= ' AND a.categorie_id = ?'; $params[] = $filters['categorie_id']; }
        if (!empty($filters['q'])) {
            $sql .= ' AND (a.titre LIKE ? OR a.description LIKE ?)';
            $params[] = '%' . $filters['q'] . '%'; $params[] = '%' . $filters['q'] . '%';
        }
        if (isset($filters['prix_min']) && $filters['prix_min'] !== '') { $sql .= ' AND a.prix >= ?'; $params[] = (int)$filters['prix_min']; }
        if (isset($filters['prix_max']) && $filters['prix_max'] !== '') { $sql .= ' AND a.prix <= ?'; $params[] = (int)$filters['prix_max']; }
        if (!empty($filters['localisation'])) { $sql .= ' AND a.localisation = ?'; $params[] = $filters['localisation']; }
        if (!empty($filters['auteur_id'])) { $sql .= ' AND a.auteur_id = ?'; $params[] = $filters['auteur_id']; }
        $sql .= ' ORDER BY a.date_creation DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;
        return $this->fetchAll($sql, $params);
    }
    public function find($id) {
        return $this->fetchOne(
            "SELECT a.*, c.nom AS categorie_nom, c.icone AS categorie_icone,
                    u.nom AS auteur_nom, u.email AS auteur_email, u.telephone AS auteur_telephone
             FROM annonces a JOIN categories c ON c.id = a.categorie_id JOIN users u ON u.id = a.auteur_id
             WHERE a.id = ?", [$id]);
    }
    public function photos($id) { return $this->fetchAll('SELECT * FROM annonce_photos WHERE annonce_id = ? ORDER BY id ASC', [$id]); }
    public function create($data) {
        $this->query('INSERT INTO annonces (titre, description, prix, categorie_id, auteur_id, localisation, statut) VALUES (?, ?, ?, ?, ?, ?, ?)',
            [$data['titre'], $data['description'], $data['prix'], $data['categorie_id'], $data['auteur_id'], $data['localisation'], $data['statut'] ?? 'en_attente']);
        return $this->lastInsertId();
    }
    public function update($id, $data) {
        $this->query('UPDATE annonces SET titre=?, description=?, prix=?, categorie_id=?, localisation=? WHERE id=?',
            [$data['titre'], $data['description'], $data['prix'], $data['categorie_id'], $data['localisation'], $id]);
    }
    public function delete($id) { $this->query('DELETE FROM annonces WHERE id = ?', [$id]); }
    public function setStatut($id, $statut) { $this->query('UPDATE annonces SET statut = ? WHERE id = ?', [$statut, $id]); }
    public function addPhoto($id, $url) { $this->query('INSERT INTO annonce_photos (annonce_id, url) VALUES (?, ?)', [$id, $url]); }
    public function deletePhotos($id) { $this->query('DELETE FROM annonce_photos WHERE annonce_id = ?', [$id]); }
    public function countByStatut($s) { $r = $this->fetchOne('SELECT COUNT(*) AS n FROM annonces WHERE statut = ?', [$s]); return (int)($r['n'] ?? 0); }
    public function listForModeration() {
        return $this->fetchAll(
            "SELECT a.*, c.nom AS categorie_nom, u.nom AS auteur_nom,
                    (SELECT url FROM annonce_photos p WHERE p.annonce_id = a.id ORDER BY p.id ASC LIMIT 1) AS photo_principale
             FROM annonces a JOIN categories c ON c.id = a.categorie_id JOIN users u ON u.id = a.auteur_id
             ORDER BY FIELD(a.statut, 'en_attente', 'active', 'rejetee', 'vendue'), a.date_creation DESC");
    }
}
