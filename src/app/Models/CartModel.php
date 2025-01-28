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
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }

    public function removeFromCart($productId)
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    public function saveCart($userId, $cart)
    {
        $sql = "INSERT INTO paniers (id_membre, contenu) VALUES (:id_membre, :contenu) ON DUPLICATE KEY UPDATE contenu = :contenu";
        return $this->query($sql, ['id_membre' => $userId, 'contenu' => json_encode($cart)]);
    }

    public function getSavedCart($userId)
    {
        $sql = "SELECT contenu FROM paniers WHERE id_membre = :id_membre";
        $stmt = $this->query($sql, ['id_membre' => $userId]);
        $result = $stmt->fetch();
        return $result ? json_decode($result['contenu'], true) : [];
    }
}
