<?php
require_once __DIR__ . '/Database.php';
class Model {
    protected $db;
    public function __construct() { $this->db = Database::getInstance()->getConnection(); }
    protected function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    protected function fetchOne($sql, $params = []) { return $this->query($sql, $params)->fetch(); }
    protected function fetchAll($sql, $params = []) { return $this->query($sql, $params)->fetchAll(); }
    protected function lastInsertId() { return $this->db->lastInsertId(); }
}
