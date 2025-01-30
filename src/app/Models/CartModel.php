<?php

namespace Models;

use Models\ModeleParent;

class CartModel extends ModeleParent
{
    public function getCartItems()
    {
        // Si l'utilisateur n'est pas connecté, retourner un panier vide (on utilise LocalStorage maintenant)
        if (!isset($_SESSION['user_id'])) {
            return [];
        }

        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("SELECT a.id_article, a.lib_article, a.prix, p.quantite 
                               FROM panier p 
                               JOIN articles a ON p.id_article = a.id_article 
                               WHERE p.id_membre = :user");
        $stmt->execute(['user' => $_SESSION['user_id']]);
        return $stmt->fetchAll();
    }

    public function getTotal()
    {
        $cartItems = $this->getCartItems();
        return array_reduce($cartItems, function ($sum, $item) {
            return $sum + ($item['prix'] * $item['quantite']);
        }, 0);
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

    public function updateCartQuantity($userId, $productId, $quantity)
    {
        $pdo = $this->getPdo();
        $stmt = $pdo->prepare("INSERT INTO panier (id_membre, id_article, quantite)
            VALUES (:user, :id, :quantity)
            ON DUPLICATE KEY UPDATE quantite = :quantity");
        $stmt->execute([
            'user' => $userId,
            'id' => $productId,
            'quantity' => $quantity
        ]);
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
