<?php
class FavorisController extends Controller {
    public function index() {
        $this->requireLogin();
        $annonces = $this->model('Favoris')->allForUser($this->currentUser()['id']);
        $this->view('favoris/index', ['titre_page' => 'Mes favoris', 'annonces' => $annonces]);
    }

    public function add($annonceId = null) {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('favoris');
        $this->verifyCsrf();

        $annonce = $this->model('Annonce')->find((int)$annonceId);
        if (!$annonce || $annonce['statut'] !== 'active') $this->redirect('annonces');

        if ($annonce['auteur_id'] != $this->currentUser()['id']) {
            $this->model('Favoris')->add($this->currentUser()['id'], $annonce['id']);
            $this->flash('success', 'Annonce ajoutee aux favoris.');
        }

        $this->redirect('annonces/show/' . $annonce['id']);
    }

    public function remove($annonceId = null) {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('favoris');
        $this->verifyCsrf();

        $this->model('Favoris')->remove($this->currentUser()['id'], (int)$annonceId);
        $this->flash('success', 'Annonce retiree des favoris.');

        $redirect = $_POST['redirect'] ?? '';
        $this->redirect($redirect === 'favoris' ? 'favoris' : 'annonces/show/' . (int)$annonceId);
    }
}

