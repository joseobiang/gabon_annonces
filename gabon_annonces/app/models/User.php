<?php
class User extends Model {
    public function findById($id) { return $this->fetchOne('SELECT * FROM users WHERE id = ?', [$id]); }
    public function findByEmail($email) { return $this->fetchOne('SELECT * FROM users WHERE email = ?', [$email]); }
    public function create($nom, $email, $password, $telephone, $role = 'annonceur') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->query('INSERT INTO users (nom, email, password, telephone, role) VALUES (?, ?, ?, ?, ?)',
            [$nom, $email, $hash, $telephone, $role]);
        return $this->lastInsertId();
    }
    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) { unset($user['password']); return $user; }
        return false;
    }
}
