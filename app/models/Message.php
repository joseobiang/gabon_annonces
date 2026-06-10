<?php
class Message extends Model {
    public function send($exp, $dest, $ann, $contenu) {
        $this->query('INSERT INTO messages (expediteur_id, destinataire_id, annonce_id, contenu) VALUES (?, ?, ?, ?)',
            [$exp, $dest, $ann, $contenu]);
        return $this->lastInsertId();
    }
    public function conversations($userId) {
        return $this->fetchAll(
            "SELECT m.annonce_id, a.titre AS annonce_titre,
                IF(m.expediteur_id = ?, m.destinataire_id, m.expediteur_id) AS interlocuteur_id,
                MAX(u.nom) AS interlocuteur_nom, MAX(m.date_envoi) AS dernier_message,
                SUM(CASE WHEN m.destinataire_id = ? AND m.lu = 0 THEN 1 ELSE 0 END) AS non_lus
             FROM messages m JOIN annonces a ON a.id = m.annonce_id
             JOIN users u ON u.id = IF(m.expediteur_id = ?, m.destinataire_id, m.expediteur_id)
             WHERE m.expediteur_id = ? OR m.destinataire_id = ?
             GROUP BY m.annonce_id, interlocuteur_id ORDER BY dernier_message DESC",
            [$userId, $userId, $userId, $userId, $userId]);
    }
    public function conversation($userId, $otherId, $annonceId) {
        return $this->fetchAll(
            "SELECT m.*, u.nom AS expediteur_nom FROM messages m JOIN users u ON u.id = m.expediteur_id
             WHERE m.annonce_id = ?
               AND ((m.expediteur_id = ? AND m.destinataire_id = ?) OR (m.expediteur_id = ? AND m.destinataire_id = ?))
             ORDER BY m.date_envoi ASC",
            [$annonceId, $userId, $otherId, $otherId, $userId]);
    }
    public function markAsRead($userId, $otherId, $annonceId) {
        $this->query('UPDATE messages SET lu = 1 WHERE destinataire_id = ? AND expediteur_id = ? AND annonce_id = ?',
            [$userId, $otherId, $annonceId]);
    }
    public function countUnread($userId) {
        $r = $this->fetchOne('SELECT COUNT(*) AS n FROM messages WHERE destinataire_id = ? AND lu = 0', [$userId]);
        return (int)($r['n'] ?? 0);
    }
}