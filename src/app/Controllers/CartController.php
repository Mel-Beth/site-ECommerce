<?php

namespace Controllers;

use Models\CartModel;

class CartController
{
    public function index()
    {
        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartItems();
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

                header('Location: cart');
                exit();
            }
        }
    }

    public function removeFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $productId = $data['remove_product_id'] ?? null;

            if ($productId) {
                $cartModel = new CartModel();
                $cartModel->removeFromCart($productId);

                echo json_encode(['success' => true]);
                exit();
            }
        }

        echo json_encode(['success' => false]);
        exit();
    }

    public function updateQuantity()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $productId = $data['product_id'] ?? null;
            $quantity = $data['quantity'] ?? 1;

            if ($productId) {
                $cartModel = new CartModel();
                $cartModel->updateCartQuantity($productId, $quantity);

                echo json_encode(['success' => true]);
                exit();
            }
        }

        echo json_encode(['success' => false]);
        exit();
    }
}
?>