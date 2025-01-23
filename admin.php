<?php
// On appelle l'autoloader de Composer
require_once("./vendor/autoload.php");

// Appel de la bibliothèque Dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Démarrage de la session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Appel du routeur admin
require("./src/admin/admin_routeur.php");