<?php
// Définir la racine du projet
define('PROJECT_ROOT', __DIR__);

// Charger l'autoloader de Composer
require_once PROJECT_ROOT . '/vendor/autoload.php';

// Démarrer la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Définir des constantes pour les noms de pages
define('PAGE_ACCUEIL', 'accueil');

// Appel des namespaces
use Controllers\AccueilController;
use Controllers\ArticlesController;
use Controllers\CartController;
use Controllers\LoginController;
use Controllers\ProductController;
use Controllers\RegisterController;
use Controllers\SearchController;
use Controllers\UserController;

// Définir la page par défaut
$page = $_GET['page'] ?? PAGE_ACCUEIL;

try {
    switch ($page) {
        case PAGE_ACCUEIL:
            $controller = new AccueilController();
            $controller->index();
            break;

        case 'articles':
            $controller = new ArticlesController();
            $controller->index();
            break;

        case 'cart':
            $controller = new CartController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                if (isset($data['remove_product_id'])) {
                    $controller->removeFromCart();
                } elseif (isset($data['product_id'])) {
                    $controller->updateQuantity();
                } else {
                    $controller->addToCart();
                }
            } else {
                $controller->index();
            }
            break;

        case 'login':
            $controller = new LoginController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->login();
            } else {
                $controller->index();
            }
            break;

        case 'register':
            $controller = new RegisterController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->register();
            } else {
                $controller->index();
            }
            break;

        case 'product':
            if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
                $controller = new ProductController();
                $controller->show((int)$_GET['id']);
            } else {
                throw new Exception("ID de produit invalide.");
            }
            break;

        case 'search':
            $controller = new SearchController();
            $controller->index();
            break;

        case 'user':
            $controller = new UserController();
            $controller->profile();
            break;

        default:
            // Page non trouvée
            header("HTTP/1.0 404 Not Found");
            include PROJECT_ROOT . '/src/app/Views/404.php';
            exit();
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: /error');
    exit();
}