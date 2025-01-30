<?php

namespace Models;

use Models\ModeleParent;

class CartModel extends ModeleParent
{
    public function getCartItems()
    {
        return $_SESSION['cart'] ?? [];
    }

    public function addToCart($productId, $quantity)
    {
        $pdo = $this->getPdo();

        // Récupérer les infos du produit
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id_article = :id");
        $stmt->execute(['id' => $productId]);
        $product = $stmt->fetch();

        if ($product && $product['quantite_stock'] >= $quantity) {
            $_SESSION['cart'][$productId] = [
                'id' => $product['id_article'],
                'name' => $product['lib_article'],
                'price' => $product['prix'],
                'quantity' => ($_SESSION['cart'][$productId]['quantity'] ?? 0) + $quantity
            ];
        } else {
            $_SESSION['cart_error'] = "Stock insuffisant pour " . $product['lib_article'];
        }
    }

    public function removeFromCart($productId)
    {
        unset($_SESSION['cart'][$productId]);
    }

    public function updateCartQuantity($productId, $quantity)
    {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public function clearCart()
    {
        $_SESSION['cart'] = [];
    }

    public function placeOrder($cart)
    {
        // Simuler l'insertion en base de données
        $_SESSION['orders'][] = [
            'id' => uniqid(),
            'date' => date('Y-m-d H:i:s'),
            'items' => $cart,
            'total' => array_reduce($cart, function ($total, $quantity) {
                return $total + ($quantity * 10); // Supposons que chaque article coûte 10€
            }, 0)
        ];

        return true;
    }
}
