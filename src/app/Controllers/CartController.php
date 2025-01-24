<?php

namespace Controllers;

use Models\CartModel;
use Models\ProductModel;

class CartController
{
    public function index()
    {
        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartItems();

        // Récupérer les détails des produits dans le panier
        $productModel = new ProductModel();
        $products = [];
        foreach ($cartItems as $productId => $quantity) {
            $product = $productModel->getProductById($productId);
            if ($product) {
                $products[] = [
                    'id_article' => $product['id_article'],
                    'lib_article' => $product['lib_article'],
                    'prix' => $product['prix'],
                    'quantity' => $quantity,
                ];
            }
        }

        // Passer les données à la vue
        include('src/app/Views/public/cart.php');
    }

    public function addToCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;

            if ($productId) {
                // Vérifiez le stock disponible
                $productModel = new ProductModel();
                $product = $productModel->getProductById($productId);

                if (!$product) {
                    // Produit non trouvé
                    $_SESSION['cart_error'] = "Produit non trouvé.";
                    header('Location: product?id=' . $productId);
                    exit();
                }

                // Vérifiez si la quantité demandée dépasse le stock disponible
                if ($quantity > $product['quantite_stock']) {
                    $_SESSION['cart_error'] = "La quantité demandée dépasse le stock disponible.";
                    header('Location: product?id=' . $productId);
                    exit();
                }

                // Ajoutez le produit au panier avec la quantité spécifiée
                $cartModel = new CartModel();
                $cartModel->addToCart($productId, $quantity);

                // Redirigez l'utilisateur vers la page du panier
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
                $cartModel->addToCart($productId, $quantity);

                echo json_encode(['success' => true]);
                exit();
            }
        }

        echo json_encode(['success' => false]);
        exit();
    }
}