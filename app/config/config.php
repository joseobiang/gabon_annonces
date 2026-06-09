<?php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'gabon_annonces');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_CHARSET', 'utf8mb4');

define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/gabon_annonces/public');
define('UPLOAD_DIR', __DIR__ . '/../../public/assets/uploads/');
define('UPLOAD_URL', BASE_URL . '/assets/uploads/');

define('APP_NAME', 'Gabon Annonces');
define('APP_DESC', 'La plateforme de petites annonces du Gabon');
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);

define('PROVINCES_GABON', [
    'Estuaire (Libreville)', 'Haut-Ogooue (Franceville)', 'Moyen-Ogooue (Lambarene)',
    'Ngounie (Mouila)', 'Nyanga (Tchibanga)', 'Ogooue-Ivindo (Makokou)',
    'Ogooue-Lolo (Koulamoutou)', 'Ogooue-Maritime (Port-Gentil)', 'Woleu-Ntem (Oyem)',
]);

ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('Africa/Libreville');

