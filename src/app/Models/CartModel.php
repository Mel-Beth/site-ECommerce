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
        // Si le panier n'existe pas encore dans la session, initialisez-le
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Si le produit est déjà dans le panier, mettez à jour la quantité
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            // Sinon, ajoutez le produit avec la quantité spécifiée
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public function removeFromCart($productId)
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }
}