<?php

namespace Controllers;

use Models\ProductModel;

class AccueilController {
    public function index() {
        // Charger le modèle ProductModel
        $productModel = new ProductModel();

        // Récupérer les articles
        try {
            $articles = $productModel->getAllProducts(); // Assurez-vous que cette méthode existe dans ProductModel
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        // Passer les données à la vue
        // echo "Chargement de la vue accueil..."; // Message de débogage
        require PROJECT_ROOT . '/src/app/Views/accueil.php';
    }
}