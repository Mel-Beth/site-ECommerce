<?php
session_start();

// Supprime toutes les variables de session
session_unset();

// Détruit la session
if (session_destroy()) {
    // Redirige vers la page de connexion
    header("Location: index");
    exit();
} else {
    die("Erreur lors de la destruction de la session.");
}
?>