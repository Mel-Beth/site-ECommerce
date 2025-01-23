<?php
// Démarrage de la session si elle n'est pas déjà active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclusion du fichier de connexion à la base de données
require_once PROJECT_ROOT . '/src/app/Models/ModeleParent.php';

// Vérification si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    // Récupération des informations de l'utilisateur depuis la session
    $userName = $_SESSION['user']['pseudo_membre'] ?? '';
} else {
    $userName = '';
}


?>