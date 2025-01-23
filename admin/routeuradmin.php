<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Inclure l'autoloader de Composer

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Démarrer la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Définir des constantes pour les noms de pages admin
define('PAGE_ADMIN', 'admin');
define('PAGE_ADMIN_LOGIN', 'admin/login');
define('PAGE_ADMIN_DASHBOARD', 'admin/dashboard');
define('PAGE_ADMIN_ORDERS', 'admin/orders');
define('PAGE_ADMIN_PRODUCTS', 'admin/products');
define('PAGE_ADMIN_USERS', 'admin/users');
define('PAGE_ADMIN_REVIEWS', 'admin/reviews');

// Appel des namespaces admin
use Admin\Controllers\AdminController;
use Admin\Controllers\DashboardController;
use Admin\Controllers\OrdersController;
use Admin\Controllers\ProductsController;
use Admin\Controllers\UsersController;
use Admin\Controllers\ReviewsController;

// Définir la page par défaut pour l'admin
$page = $_GET['page'] ?? PAGE_ADMIN;

try {
    switch ($page) {
        case PAGE_ADMIN:
            $controller = new AdminController();
            $controller->index();
            break;

        case PAGE_ADMIN_LOGIN:
            $controller = new AdminController();
            $controller->login();
            break;

        case PAGE_ADMIN_DASHBOARD:
            // Vérifier si l'utilisateur est connecté en tant qu'admin
            if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
                header('Location: /admin');
                exit();
            }
            $controller = new DashboardController();
            $controller->index();
            break;

        case PAGE_ADMIN_ORDERS:
            // Vérifier si l'utilisateur est connecté en tant qu'admin
            if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
                header('Location: /admin');
                exit();
            }
            $controller = new OrdersController();
            $controller->index();
            break;

        case PAGE_ADMIN_PRODUCTS:
            // Vérifier si l'utilisateur est connecté en tant qu'admin
            if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
                header('Location: /admin');
                exit();
            }
            $controller = new ProductsController();
            $controller->index();
            break;

        case PAGE_ADMIN_USERS:
            // Vérifier si l'utilisateur est connecté en tant qu'admin
            if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
                header('Location: /admin');
                exit();
            }
            $controller = new UsersController();
            $controller->index();
            break;

        case PAGE_ADMIN_REVIEWS:
            // Vérifier si l'utilisateur est connecté en tant qu'admin
            if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
                header('Location: /admin');
                exit();
            }
            $controller = new ReviewsController();
            $controller->index();
            break;

        default:
            // Page non trouvée
            header("HTTP/1.0 404 Not Found");
            include __DIR__ . '/../../src/admin/Views/404.php';
            exit();
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: /admin/error');
    exit();
}
