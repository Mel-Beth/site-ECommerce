<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà active
}
// Connexion à la base de données
include 'php/db.php';

// Charger les traductions
$translations = include 'includes/translations.php';
$lang = $_SESSION['lang'] ?? 'fr'; // Langue par défaut : français
$t = $translations[$lang]; // Charger les traductions pour la langue actuelle

// Gérer le changement de langue
if (isset($_GET['lang'])) {
    $newLang = htmlspecialchars($_GET['lang']); // Sécuriser l'entrée
    if (array_key_exists($newLang, $translations)) { // Vérifier si la langue est supportée
        $_SESSION['lang'] = $newLang;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    // Récupérer les informations de l'utilisateur
    $userName = $_SESSION['user']['nom'] ?? '';
    $userPrenom = $_SESSION['user']['prenom'] ?? '';
} else {
    $userName = '';
    $userPrenom = '';
}

// Initialisation du panier
$cartItems = $_SESSION['cart'] ?? [];
if (!is_array($cartItems)) {
    $cartItems = []; // Garantir un tableau vide si nécessaire
}
?>
