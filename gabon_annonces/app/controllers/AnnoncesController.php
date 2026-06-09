<?php
class AnnoncesController extends Controller {
    public function index() {
        $filters = [
            'q' => trim($_GET['q'] ?? ''),
            'categorie_id' => $_GET['categorie_id'] ?? '',
            'prix_min' => $_GET['prix_min'] ?? '',
            'prix_max' => $_GET['prix_max'] ?? '',
            'localisation' => $_GET['localisation'] ?? '',
            'statut' => 'active',
        ];
        $annonces = $this->model('Annonce')->search($filters, 60, 0);
        $categories = $this->model('Categorie')->all();
        $this->view('annonces/index', ['titre_page'=>'Toutes les annonces','annonces'=>$annonces,'categories'=>$categories,'filters'=>$filters]);
    }
    public function show($id = null) {
        if (!$id) $this->redirect('annonces');
        $am = $this->model('Annonce');
        $annonce = $am->find((int)$id);
        if (!$annonce) { http_response_code(404); $this->view('errors/404', ['titre_page'=>'Introuvable']); return; }
        if ($annonce['statut'] !== 'active') {
            $u = $this->currentUser();
            if (!$u || ($u['id'] != $annonce['auteur_id'] && $u['role'] !== 'moderateur')) {
                http_response_code(404); $this->view('errors/404', ['titre_page'=>'Introuvable']); return;
            }
        }
        $photos = $am->photos($annonce['id']);
        $isFavori = $this->isLoggedIn() ? $this->model('Favoris')->isFavori($this->currentUser()['id'], $annonce['id']) : false;
        $this->view('annonces/show', ['titre_page'=>$annonce['titre'],'annonce'=>$annonce,'photos'=>$photos,'isFavori'=>$isFavori]);
    }
    public function create() {
        $this->requireLogin();
        $categories = $this->model('Categorie')->all();
        $errors = []; $old = ['titre'=>'','description'=>'','prix'=>'','categorie_id'=>'','localisation'=>''];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            foreach (['titre','description','prix','categorie_id','localisation'] as $k) $old[$k] = trim($_POST[$k] ?? '');
            if (strlen($old['titre']) < 4) $errors[] = 'Titre trop court.';
            if (strlen($old['description']) < 10) $errors[] = 'Description trop courte.';
            if (!ctype_digit((string)$old['prix'])) $errors[] = 'Prix invalide.';
            if (!$old['categorie_id']) $errors[] = 'Categorie requise.';
            if (!in_array($old['localisation'], PROVINCES_GABON)) $errors[] = 'Localisation invalide.';
            if (empty($errors)) {
                $am = $this->model('Annonce');
                $id = $am->create([
                    'titre'=>$old['titre'],'description'=>$old['description'],'prix'=>(int)$old['prix'],
                    'categorie_id'=>(int)$old['categorie_id'],'auteur_id'=>$this->currentUser()['id'],
                    'localisation'=>$old['localisation'],'statut'=>'en_attente',
                ]);
                $this->handlePhotosUpload($id, $am);
                $this->flash('success', 'Annonce creee. En attente de validation.');
                $this->redirect('annonces/mine');
            }
        }
        $this->view('annonces/create', ['titre_page'=>'Publier','categories'=>$categories,'errors'=>$errors,'old'=>$old]);
    }
    public function edit($id = null) {
        $this->requireLogin();
        if (!$id) $this->redirect('annonces');
        $am = $this->model('Annonce');
        $annonce = $am->find((int)$id);
        if (!$annonce) $this->redirect('annonces');
        $u = $this->currentUser();
        if ($u['id'] != $annonce['auteur_id'] && $u['role'] !== 'moderateur') { http_response_code(403); die('Acces refuse.'); }
        $categories = $this->model('Categorie')->all();
        $errors = [];
        $old = ['titre'=>$annonce['titre'],'description'=>$annonce['description'],'prix'=>$annonce['prix'],
                'categorie_id'=>$annonce['categorie_id'],'localisation'=>$annonce['localisation']];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            foreach (['titre','description','prix','categorie_id','localisation'] as $k) $old[$k] = trim($_POST[$k] ?? '');
            if (strlen($old['titre']) < 4) $errors[] = 'Titre trop court.';
            if (strlen($old['description']) < 10) $errors[] = 'Description trop courte.';
            if (!ctype_digit((string)$old['prix'])) $errors[] = 'Prix invalide.';
            if (!$old['categorie_id']) $errors[] = 'Categorie requise.';
            if (!in_array($old['localisation'], PROVINCES_GABON)) $errors[] = 'Localisation invalide.';
            if (empty($errors)) {
                $am->update($annonce['id'], ['titre'=>$old['titre'],'description'=>$old['description'],'prix'=>(int)$old['prix'],
                    'categorie_id'=>(int)$old['categorie_id'],'localisation'=>$old['localisation']]);
                $this->handlePhotosUpload($annonce['id'], $am);
                $this->flash('success', 'Annonce mise a jour.');
                $this->redirect('annonces/show/' . $annonce['id']);
            }
        }
        $photos = $am->photos($annonce['id']);
        $this->view('annonces/edit', ['titre_page'=>'Modifier','annonce'=>$annonce,'categories'=>$categories,'errors'=>$errors,'old'=>$old,'photos'=>$photos]);
    }
    public function delete($id = null) {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('annonces');
        $this->verifyCsrf();
        $am = $this->model('Annonce');
        $annonce = $am->find((int)$id);
        if (!$annonce) $this->redirect('annonces');
        $u = $this->currentUser();
        if ($u['id'] != $annonce['auteur_id'] && $u['role'] !== 'moderateur') { http_response_code(403); die('Acces refuse.'); }
        foreach ($am->photos($annonce['id']) as $p) {
            $path = UPLOAD_DIR . basename($p['url']);
            if (file_exists($path)) @unlink($path);
        }
        $am->delete($annonce['id']);
        $this->flash('success', 'Annonce supprimee.');
        $this->redirect('annonces/mine');
    }
    public function vendre($id = null) {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('annonces');
        $this->verifyCsrf();
        $am = $this->model('Annonce');
        $annonce = $am->find((int)$id);
        if (!$annonce) $this->redirect('annonces');
        if ($this->currentUser()['id'] != $annonce['auteur_id']) { http_response_code(403); die('Acces refuse.'); }
        $am->setStatut($annonce['id'], 'vendue');
        $this->flash('success', 'Annonce marquee comme vendue.');
        $this->redirect('annonces/mine');
    }
    public function mine() {
        $this->requireLogin();
        $this->view('annonces/mine', ['titre_page'=>'Mes annonces','annonces'=>$this->myAnnonces()]);
    }
    private function myAnnonces() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "SELECT a.*, c.nom AS categorie_nom, c.icone AS categorie_icone,
                    (SELECT url FROM annonce_photos p WHERE p.annonce_id = a.id ORDER BY p.id ASC LIMIT 1) AS photo_principale
             FROM annonces a JOIN categories c ON c.id = a.categorie_id
             WHERE a.auteur_id = ? ORDER BY a.date_creation DESC");
        $stmt->execute([$this->currentUser()['id']]);
        return $stmt->fetchAll();
    }
    private function handlePhotosUpload($id, $am) {
        if (empty($_FILES['photos']['name'][0])) return;
        if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0775, true);
        $n = count($_FILES['photos']['name']);
        for ($i = 0; $i < $n; $i++) {
            if ($_FILES['photos']['error'][$i] !== UPLOAD_ERR_OK) continue;
            if ($_FILES['photos']['size'][$i] > MAX_UPLOAD_SIZE) continue;
            $ext = strtolower(pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext, ALLOWED_EXTENSIONS)) continue;
            $newName = 'annonce_' . $id . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['photos']['tmp_name'][$i], UPLOAD_DIR . $newName))
                $am->addPhoto($id, UPLOAD_URL . $newName);
        }
    }
}
