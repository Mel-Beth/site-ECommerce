<?php
error_log("🔍 Route demandée : " . ($_GET["route"] ?? "Aucune route détectée"));

$_GET["route"] = trim($_GET["route"] ?? '', "/");
$route = explode("/", $_GET["route"]);
$route = array_map('htmlspecialchars', $route);

if (empty($route[0])) {
    (new Controllers\HomeController())->index();
} else {
    try {
        switch ($route[0]) {
            case 'accueil':
                (new Controllers\HomeController())->index();
                break;

            case 'product':
                if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
                    (new Controllers\ProductsController())->show((int)$_GET['id']);
                } else {
                    throw new Exception("ID de produit invalide.");
                }
                break;

            case 'products':
                $controller = new Controllers\ProductsController();
                if (isset($route[1]) && $route[1] === 'admin') {
                    $controller->index();
                } else {
                    $controller->publicArticles();
                }
                break;
                break; // ✅ Correction : éviter de tomber sur 'cart'

            case 'cart':
                $controller = new Controllers\CartController();

                // ✅ Vérification correcte des sous-routes
                if (isset($route[1])) {
                    switch ($route[1]) {
                        case 'updateQuantity':
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $controller->updateQuantity();
                            }
                            break;

                        case 'removeFromCart':
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $controller->removeFromCart();
                            }
                            break;

                        case 'applyCoupon':
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $controller->applyCoupon();
                            }
                            break;

                        case 'validateOrder':
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $controller->validateOrder();
                            }
                            break;

                        case 'addToCart': // ✅ Correction de l'ajout au panier
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $controller->addToCart();
                            } else {
                                throw new Exception("Méthode non autorisée.");
                            }
                            break;

                        default:
                            include('src/app/Views/404.php');
                            exit();
                    }
                } else {
                    $controller->index();
                }
                break;

            case 'login':
                $controller = new Controllers\AuthController();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->login();
                } else {
                    $controller->index();
                }
                break;

            case 'register':
                $controller = new Controllers\AuthController();
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->register();
                } else {
                    $controller->index();
                }
                break;

            case 'logout':
                session_destroy();
                header('Location: login');
                exit();
                break;

            case 'user':
                (new Controllers\UserController())->profile();
                break;

            case 'admin':
                if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 1) {
                    header('Location: login');
                    exit();
                }

                switch ($route[1] ?? 'dashboard') {
                    case 'dashboard':
                        (new Controllers\DashboardController())->index();
                        break;

                    case 'orders':
                        $controller = new Controllers\OrderController();
                        if (isset($route[2]) && $route[2] === 'generateInvoice' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->generateInvoice((int)$route[3]);
                        } else {
                            $controller->index();
                        }
                        break;

                    case 'products':
                        $controller = new Controllers\ProductsController();
                        if (!isset($route[2])) {
                            $controller->index();
                        } elseif ($route[2] === 'add') {
                            $controller->add();
                            $controller->save();
                        } elseif ($route[2] === 'edit' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->edit((int)$route[3]);
                        } elseif ($route[2] === 'delete' && isset($route[3]) && ctype_digit($route[3])) {
                            $controller->delete((int)$route[3]);
                        } elseif ($route[2] === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->update();
                        }
                        break;

                    case 'reviews':
                        $controller = new Controllers\ReviewsController();
                        if (isset($route[2]) && !empty($route[2])) {
                            if ($route[2] === 'approve' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reviewId'])) {
                                $controller->approve($_POST['reviewId']);
                                exit();
                            } elseif ($route[2] === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reviewId'])) {
                                $controller->delete($_POST['reviewId']);
                                exit();
                            } else {
                                include('src/app/Views/404.php');
                                exit();
                            }
                        } else {
                            $controller->index();
                            exit();
                        }

                    case 'users':
                        (new Controllers\UserController())->listUsers();
                        break;

                    case 'stats':
                        (new Controllers\DashboardController())->index();
                        break;

                    case 'settings':
                        $controller = new Controllers\SettingsController();
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (isset($route[1]) && $route[1] === 'update') {
                                $controller->updateSettings();
                            } elseif (isset($route[1]) && $route[1] === 'updatePassword') {
                                $controller->updatePassword();
                            }
                        } else {
                            $controller->index();
                        }
                        break;

                    default:
                        include('src/app/Views/404.php');
                        exit();
                }
                break;

            case 'payment':
                (new Controllers\PaymentController())->createSession();
                break;

            case 'success':
                (new Controllers\PaymentController())->paymentSuccess();
                break;

            case 'cancel':
                (new Controllers\PaymentController())->paymentCancel();
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
