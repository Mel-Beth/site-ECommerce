<?php

// On retire les '/' en début et fin de l'URI
$_GET["route"] = trim($_GET["route"], "/");

// On parse la variable de route dans un tableau 
$route = explode("/", $_GET["route"]);

// Routage en fonction de la première case du tableau

// S'il n'existe pas de première case du tableau (rien dans l'URL après le nom du site)
// alors on considère qu'on veut arriver sur la page d'accueil
if (!isset($route[0]) || empty($route[0])) {
    include("src/app/Views/public/accueil.php");
} else {
    switch ($route[0]) {
        case 'accueil':
            $controller = new Controllers\AccueilController();
            $controller->index();
            break;

        case 'articles':
            $controller = new Controllers\ArticlesController();
            $controller->index();
            break;

        case 'cart':
            $controller = new Controllers\CartController();
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
            $controller = new Controllers\LoginController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->login();
            } else {
                $controller->index();
            }
            break;

        case 'register':
            $controller = new Controllers\RegisterController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->register();
            } else {
                $controller->index();
            }
            break;

        case 'product':
            if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
                $controller = new Controllers\ProductController();
                $controller->show((int)$_GET['id']);
            } else {
                throw new Exception("ID de produit invalide.");
            }
            break;

        case 'search':
            $controller = new Controllers\SearchController();
            $controller->index();
            break;

        case 'user':
            $controller = new Controllers\UserController();
            $controller->profile();
            break;

        case 'logout':
            require('logout.php');
            break;

        // Routes pour l'administration
        case 'admin':
            // Vérifier si l'utilisateur est connecté en tant qu'admin
            if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
                header('Location: admin');
                exit();
            }

            // Récupérer la sous-route admin
            $adminRoute = $route[1] ?? 'dashboard'; // Correction ici

            switch ($adminRoute) {
                case 'dashboard':
                    $controller = new Controllers\DashboardController();
                    $controller->index();
                    break;

                case 'orders':
                    $controller = new Controllers\OrderController(); // Correction ici (OrderController au lieu de OrdersController)
                    $controller->index();
                    break;

                case 'products':
                    $controller = new Controllers\ProductController();
                    $controller->index();
                    break;

                case 'users':
                    $controller = new Controllers\UserController(); // Correction ici (UserController au lieu de UsersController)
                    $controller->profile();
                    break;

                case 'reviews':
                    $controller = new Controllers\ReviewController(); // Correction ici (ReviewController au lieu de ReviewsController)
                    $controller->index();
                    break;

                default:
                    // Page admin non trouvée
                    include('src/app/Views/404.php');
                    exit();
            }
            break;

        default:
            // Page non trouvée
            include('src/app/Views/404.php');
            exit();
    }
}