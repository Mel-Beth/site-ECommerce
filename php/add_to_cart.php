<?php

// Vérifier si le produit est envoyé via POST
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    // Vérifier si le panier existe dans la session, sinon l'initialiser
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Si la quantité est valide, ajouter ou mettre à jour le produit dans le panier
    if ($quantity > 0) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity; // Mettre à jour la quantité si le produit existe déjà
        } else {
            $_SESSION['cart'][$productId] = $quantity; // Ajouter le produit si ce n'est pas encore dans le panier
        }
    }
}

// Rediriger vers le panier
header('Location: ../cart.php');
exit();
