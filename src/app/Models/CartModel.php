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
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $quantity;
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
