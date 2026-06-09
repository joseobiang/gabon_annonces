<?php
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function format_prix($prix) {
    return number_format((float)$prix, 0, ',', ' ') . ' FCFA';
}

function format_date_fr($date) {
    if (!$date) return '';
    return date('d/m/Y H:i', strtotime($date));
}

function icone_categorie($icone) {
    $icones = [
        'car' => 'Voiture',
        'home' => 'Maison',
        'phone' => 'Telephone',
        'shirt' => 'Mode',
        'sofa' => 'Maison',
        'briefcase' => 'Emploi',
        'tools' => 'Services',
        'bike' => 'Loisirs',
        'paw' => 'Animaux',
        'box' => 'Autres',
    ];
    return $icones[$icone] ?? 'Annonce';
}


