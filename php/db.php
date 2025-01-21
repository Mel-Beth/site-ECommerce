<?php
// Configuration des paramètres de la base de données
$host = 'localhost'; // Adresse de l'hôte PostgreSQL
$port = 5432; // Port PostgreSQL
$dbname = 'ecommerce'; // Nom de votre base de données
$user = 'postgres'; // Nom d'utilisateur PostgreSQL
$password = 'postgres'; // Mot de passe PostgreSQL

try {
    // Création d'une instance PDO pour la connexion
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activer les exceptions pour les erreurs
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Mode de récupération des données par défaut
    
} catch (PDOException $e) {
    // Afficher une erreur en cas de problème de connexion
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
