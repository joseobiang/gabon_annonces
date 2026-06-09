<?php
class Categorie extends Model {
    public function all() { return $this->fetchAll('SELECT * FROM categories ORDER BY nom ASC'); }
    public function find($id) { return $this->fetchOne('SELECT * FROM categories WHERE id = ?', [$id]); }
    public function allWithCount() {
        return $this->fetchAll(
            "SELECT c.*, (SELECT COUNT(*) FROM annonces a WHERE a.categorie_id = c.id AND a.statut = 'active') AS nb_annonces
             FROM categories c ORDER BY c.nom ASC");
    }
}
