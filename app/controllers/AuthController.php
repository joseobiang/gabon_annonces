<?php
class AuthController extends Controller {
    public function register() {
        if ($this->isLoggedIn()) $this->redirect('');
        $errors = []; $old = ['nom'=>'','email'=>'','telephone'=>'','role'=>'annonceur'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $old['nom'] = trim($_POST['nom'] ?? '');
            $old['email'] = trim($_POST['email'] ?? '');
            $old['telephone'] = trim($_POST['telephone'] ?? '');
            $old['role'] = in_array($_POST['role'] ?? '', ['annonceur','acheteur']) ? $_POST['role'] : 'annonceur';
            $pwd = $_POST['password'] ?? ''; $pwd2 = $_POST['password_confirm'] ?? '';
            if ($old['nom'] === '') $errors[] = 'Le nom est requis.';
            if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
            if (strlen($pwd) < 6) $errors[] = 'Mot de passe : 6 caracteres minimum.';
            if ($pwd !== $pwd2) $errors[] = 'Les mots de passe ne correspondent pas.';
            $um = $this->model('User');
            if (empty($errors) && $um->findByEmail($old['email'])) $errors[] = 'Cet email est deja utilise.';
            if (empty($errors)) {
                $id = $um->create($old['nom'], $old['email'], $pwd, $old['telephone'], $old['role']);
                $user = $um->findById($id); unset($user['password']);
                $_SESSION['user'] = $user;
                $this->flash('success', 'Bienvenue ' . $user['nom'] . ' !');
                $this->redirect('');
            }
        }
        $this->view('auth/register', ['titre_page'=>'Inscription','errors'=>$errors,'old'=>$old]);
    }
    public function login() {
        if ($this->isLoggedIn()) $this->redirect('');
        $errors = []; $email = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $email = trim($_POST['email'] ?? ''); $pwd = $_POST['password'] ?? '';
            $user = $this->model('User')->verifyPassword($email, $pwd);
            if ($user) {
                $_SESSION['user'] = $user;
                $this->flash('success', 'Connecte(e) en tant que ' . $user['nom']);
                $this->redirect('');
            } else $errors[] = 'Email ou mot de passe incorrect.';
        }
        $this->view('auth/login', ['titre_page'=>'Connexion','errors'=>$errors,'email'=>$email]);
    }
    public function logout() { session_destroy(); $this->redirect(''); }
}
