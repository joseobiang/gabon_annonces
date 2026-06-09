<?php
class MessagesController extends Controller {
    public function index() {
        $this->requireLogin();
        $conversations = $this->model('Message')->conversations($this->currentUser()['id']);
        $this->view('messages/index', ['titre_page'=>'Messagerie','conversations'=>$conversations]);
    }
    public function show($annonceId = null, $otherId = null) {
        $this->requireLogin();
        if (!$annonceId || !$otherId) $this->redirect('messages');
        $userId = $this->currentUser()['id'];
        $annonce = $this->model('Annonce')->find((int)$annonceId);
        $other = $this->model('User')->findById((int)$otherId);
        if (!$annonce || !$other) $this->redirect('messages');
        $mm = $this->model('Message');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $contenu = trim($_POST['contenu'] ?? '');
            if ($contenu !== '') $mm->send($userId, (int)$otherId, (int)$annonceId, $contenu);
            $this->redirect('messages/show/' . (int)$annonceId . '/' . (int)$otherId);
        }
        $mm->markAsRead($userId, (int)$otherId, (int)$annonceId);
        $messages = $mm->conversation($userId, (int)$otherId, (int)$annonceId);
        $this->view('messages/show', ['titre_page'=>'Conversation avec '.$other['nom'],'annonce'=>$annonce,'other'=>$other,'messages'=>$messages]);
    }
    public function start($annonceId = null) {
        $this->requireLogin();
        if (!$annonceId) $this->redirect('annonces');
        $annonce = $this->model('Annonce')->find((int)$annonceId);
        if (!$annonce) $this->redirect('annonces');
        $userId = $this->currentUser()['id'];
        if ($userId == $annonce['auteur_id']) {
            $this->flash('error', 'Vous ne pouvez pas vous envoyer un message.');
            $this->redirect('annonces/show/' . $annonceId);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $contenu = trim($_POST['contenu'] ?? '');
            if ($contenu !== '') {
                $this->model('Message')->send($userId, $annonce['auteur_id'], $annonce['id'], $contenu);
                $this->flash('success', 'Message envoye.');
                $this->redirect('messages/show/' . $annonce['id'] . '/' . $annonce['auteur_id']);
            }
        }
        $this->redirect('annonces/show/' . $annonce['id']);
    }
}
