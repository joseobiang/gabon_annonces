<?php
class AdminController extends Controller {
    public function __construct() { $this->requireRole('moderateur'); }
    public function index() {
        $am = $this->model('Annonce');
        $annonces = $am->listForModeration();
        $stats = [
            'en_attente' => $am->countByStatut('en_attente'),
            'active'     => $am->countByStatut('active'),
            'rejetee'    => $am->countByStatut('rejetee'),
            'vendue'     => $am->countByStatut('vendue'),
        ];
        $this->view('admin/dashboard', ['titre_page'=>'Moderation','annonces'=>$annonces,'stats'=>$stats]);
    }
    public function approve($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('admin');
        $this->verifyCsrf();
        if ($id) { $this->model('Annonce')->setStatut((int)$id, 'active'); $this->flash('success','Annonce approuvee.'); }
        $this->redirect('admin');
    }
    public function reject($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('admin');
        $this->verifyCsrf();
        if ($id) { $this->model('Annonce')->setStatut((int)$id, 'rejetee'); $this->flash('success','Annonce rejetee.'); }
        $this->redirect('admin');
    }
    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('admin');
        $this->verifyCsrf();
        if ($id) {
            $am = $this->model('Annonce');
            foreach ($am->photos((int)$id) as $p) {
                $path = UPLOAD_DIR . basename($p['url']);
                if (file_exists($path)) @unlink($path);
            }
            $am->delete((int)$id);
            $this->flash('success','Annonce supprimee.');
        }
        $this->redirect('admin');
    }
}
