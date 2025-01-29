<?php
// On retire les '/' en début et fin de l'URI
$_GET["route"] = trim($_GET["route"] ?? '', "/");

// On parse la variable de route dans un tableau
$route = explode("/", $_GET["route"]);

// Nettoyage et validation de la route
$route = array_map('htmlspecialchars', $route);

// Routage en fonction de la première case du tableau
if (empty($route[0])) {
    // Route par défaut - Accueil
    $controller = new Controllers\HomeController();
    $controller->index();
} else {
    try {
        switch ($route[0]) {
            case 'accueil':
                $controller = new Controllers\HomeController();
                $controller->index();
                break;

                // Route pour afficher un produit
            case 'product':
                if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
                    $controller = new Controllers\ProductsController();
                    $controller->show((int)$_GET['id']);
                } else {
                    throw new Exception("ID de produit invalide.");
                }
                break;

                // Route pour afficher tous les produits, publique ou admin
            case 'products':
                $controller = new Controllers\ProductsController();
                if (isset($route[1]) && $route[1] === 'admin') {
                    // Route admin
                    $controller->index();
                } else {
                    // Route publique
                    $controller->publicArticles();
                }
                break;

            case 'articles':
                $controller = new Controllers\ProductsController();
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
                session_destroy(); // Détruit la session
                header('Location: login'); // Redirige vers la page de connexion
                exit();
                break;

            case 'user':
                $controller = new Controllers\UserController();
                $controller->profile();
                break;

                // Ajustez cette partie pour que la route admin soit correctement traitée
            case 'admin':
                if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 1) {
                    header('Location: login');
                    exit();
                }

                // Récupère la sous-route admin (comme orders, products, etc.)
                $adminRoute = $route[1] ?? 'dashboard'; // Par défaut, redirige vers 'dashboard'

                switch ($adminRoute) {
                    case 'dashboard':
                        $controller = new Controllers\DashboardController();
                        $controller->index();  // Assurez-vous que l'index du DashboardController est bien appelé
                        break;

                    case 'orders':
                        $controller = new Controllers\OrderController();
                        if (isset($route[2]) && $route[2] == 'generateInvoice') {
                            if (isset($route[3]) && ctype_digit($route[3])) {
                                $controller->generateInvoice((int)$route[3]);  // $route[3] contiendra l'ID de la commande
                            } else {
                                include('src/app/Views/404.php');
                                exit();
                            }
                        } else {
                            $controller->index();
                        }
                        break;

                        // Ajoutez d'autres cases comme 'products', 'users', etc., selon vos besoins
                    case 'products':
                        // Pour la gestion des produits
                        $controller = new Controllers\ProductsController();

                        // Route pour afficher la liste des produits
                        if (!isset($route[2])) {
                            $controller->index();
                        }

                        // Route pour ajouter un produit
                        elseif ($route[2] === 'add') {
                            $controller->add();
                        }

                        // Route pour éditer un produit
                        elseif ($route[2] === 'edit' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->edit((int)$route[3]);
                        }

                        // Route pour supprimer un produit
                        elseif ($route[2] === 'delete' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->delete((int)$route[3]);
                        }
                        break;

                    case 'users':
                        $controller = new Controllers\UserController();
                        $controller->listUsers();
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
        error_log($e->getMessage());
        include('src/app/Views/404.php');
        exit();
    }
}
