<?php
class HomeController extends Controller {
    public function index() {
        $annoncesRecentes = $this->model('Annonce')->search(['statut' => 'active'], 12, 0);
        $categories = $this->model('Categorie')->allWithCount();
        $this->view('home/index', [
            'titre_page' => 'Accueil',
            'annoncesRecentes' => $annoncesRecentes,
            'categories' => $categories,
        ]);
    }
    public function notFound() {
        http_response_code(404);
        $this->view('errors/404', ['titre_page' => 'Page introuvable']);
    }
}
