<?php
// On démarre la session
session_start();

// On appelle l'autoloader de Composer
require_once("./vendor/autoload.php");

// Appel de la bibliothèque Dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Vérification des variables d'environnement essentielles
if (!isset($_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'])) {
    die("Les variables d'environnement essentielles ne sont pas définies.");
}

// Appel du routeur
require("./routeur.php");
?>