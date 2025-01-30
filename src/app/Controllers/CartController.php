<?php

namespace Controllers;

use Models\CartModel;

class CartController
{
    public function index()
    {
       // Si l'utilisateur n'est pas connecté, le panier sera géré par LocalStorage en JS
    if (!isset($_SESSION['user_id'])) {
        $products = [];
    } else {
        $cartModel = new CartModel();
        $products = $cartModel->getCartItems();
    }

    // Définition des valeurs par défaut pour éviter les erreurs dans la vue
    $shippingCost = 0;
    $discount = 0;

    include('src/app/Views/public/cart.php');
    }


    public function addToCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;

            if ($productId) {
                $cartModel = new CartModel();
                $cartModel->addToCart($productId, $quantity);

                if (isset($_SESSION['cart_error'])) {
                    $_SESSION['cart_message'] = $_SESSION['cart_error'];
                    unset($_SESSION['cart_error']);
                } else {
                    $_SESSION['cart_message'] = "Article ajouté au panier ✅";
                }

                header('Location: cart');
                exit();
            }
        }
    }

    public function removeFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $productId = $data['product_id'] ?? null;

            if ($productId) {
                $cartModel = new CartModel();
                $cartModel->removeFromCart($productId);

                // Calcul du total mis à jour
                $cartItems = $cartModel->getCartItems();
                $newTotal = array_reduce($cartItems, function ($total, $item) {
                    return $total + ($item['prix'] * $item['quantity']);
                }, 0);

                echo json_encode(['success' => true, 'new_total' => number_format($newTotal, 2) . " €"]);
                exit();
            }
        }

        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
        exit();
    }

    public function updateCartQuantity()
    {
        session_start();
        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? null;
        $quantity = $data['quantity'] ?? 1;

        if ($productId) {
            $cartModel = new CartModel();
            $cartModel->updateCartQuantity($_SESSION['user_id'], $productId, $quantity);
            echo json_encode(["success" => true, "new_total" => $cartModel->getTotal()]);
            exit();
        }

        echo json_encode(["error" => "Requête invalide"]);
        exit();
    }

    public function clearCart()
    {
        $cartModel = new CartModel();
        $cartModel->clearCart();
        $_SESSION['cart_message'] = "Panier vidé avec succès.";
        header('Location: cart');
        exit();
    }

    public function applyCoupon()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $couponCode = $_POST['coupon'] ?? '';

            // Simule un code promo
            $validCoupons = [
                'PROMO10' => 10.00,
                'PROMO5' => 5.00
            ];

            if (isset($validCoupons[$couponCode])) {
                $_SESSION['discount'] = $validCoupons[$couponCode];
                $_SESSION['coupon_applied'] = true;
            } else {
                $_SESSION['discount'] = 0;
                $_SESSION['coupon_applied'] = false;
            }

            header('Location: cart');
            exit();
        }
    }

    public function validateOrder()
    {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: cart');
            exit();
        }

        $cartModel = new CartModel();
        $orderSuccess = $cartModel->placeOrder($_SESSION['cart']);

        if ($orderSuccess) {
            $_SESSION['cart'] = [];
            $_SESSION['order_success'] = "Votre commande a bien été validée ✅.";
        } else {
            $_SESSION['order_error'] = "Une erreur s'est produite lors de la validation de la commande ❌.";
        }

        header('Location: cart');
        exit();
    }
}
