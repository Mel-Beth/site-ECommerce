<?php
// On retire les '/' en début et fin de l'URI
$_GET["route"] = trim($_GET["route"] ?? '', "/");

// On parse la variable de route dans un tableau
$route = explode("/", $_GET["route"]);

// Nettoyage et validation de la route
$route = array_map('htmlspecialchars', $route);

// Routage en fonction de la première case du tableau
if (empty($route[0])) {
    $controller = new Controllers\HomeController();
    $controller->index();
} else {
    try {
        switch ($route[0]) {
            case 'accueil':
                $controller = new Controllers\HomeController();
                $controller->index();
                break;

            case 'product':
                $controller = new Controllers\ProductController();
                if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
                    $controller->show((int)$_GET['id']);
                } else {
                    throw new Exception("ID de produit invalide.");
                }
                break;

            case 'products':
                $controller = new Controllers\ProductController();
                $controller->index();
                break;

            case 'articles':
                $controller = new Controllers\ProductController();
                $controller->publicArticles();
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
            case 'register':
                $controller = new Controllers\AuthController();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $route[0] === 'login' ? $controller->login() : $controller->register();
                } else {
                    $controller->index();
                }
                break;

            case 'logout':
                session_destroy();
                header('Location: login');
                break;

            case 'user':
                $controller = new Controllers\UserController();
                $controller->profile();
                break;

                // Routes pour l'administration
            case 'admin':
                if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 1) {
                    header('Location: login');
                    exit();
                }

                $adminRoute = $route[1] ?? 'dashboard';

                switch ($adminRoute) {
                    case 'dashboard':
                        $controller = new Controllers\DashboardController();
                        $controller->index();
                        break;

                    case 'orders':
                        $controller = new Controllers\OrderController();
                        if (isset($route[2]) && $route[2] === 'details' && isset($route[3])) {
                            $controller->show((int)$route[3]);
                        } else {
                            $controller->index();
                        }
                        break;

                    case 'products':
                        $controller = new Controllers\ProductController();
                        if (isset($route[2]) && $route[2] === 'edit' && isset($route[3])) {
                            $controller->edit((int)$route[3]);
                        } else {
                            $controller->index();
                        }
                        break;

                    case 'users':
                        $controller = new Controllers\UserController();
                        $controller->profile();
                        break;

                    default:
                        include('src/app/Views/404.php');
                        exit();
                }
                break;

            default:
                include('src/app/Views/404.php');
                exit();
        }
    } catch (Exception $e) {
        // Gestion des erreurs
        error_log($e->getMessage());
        include('src/app/Views/404.php'); // Vue pour les erreurs internes
        exit();
    }
}
