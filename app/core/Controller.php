<?php
class Controller {
    protected function model($name) {
        $file = __DIR__ . '/../models/' . $name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return new $name();
        }
        die("Modele introuvable : $name");
    }

    protected function view($path, $data = []) {
        extract($data);
        $file = __DIR__ . '/../views/' . $path . '.php';
        if (!file_exists($file)) die("Vue introuvable : $path");

        require_once __DIR__ . '/../views/layouts/helpers.php';
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once $file;
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    protected function redirect($path = '') {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function isLoggedIn() { return isset($_SESSION['user']); }
    protected function currentUser() { return $_SESSION['user'] ?? null; }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $_SESSION['flash_error'] = 'Vous devez etre connecte pour acceder a cette page.';
            $this->redirect('auth/login');
        }
    }

    protected function requireRole($role) {
        $this->requireLogin();
        if ($_SESSION['user']['role'] !== $role) {
            http_response_code(403);
            die('Acces refuse.');
        }
    }

    protected function csrfToken() {
        if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('Token CSRF invalide.');
        }
    }

    protected function flash($key, $message) { $_SESSION['flash_' . $key] = $message; }
}

