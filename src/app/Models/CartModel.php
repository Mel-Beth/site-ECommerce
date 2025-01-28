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
}
?>