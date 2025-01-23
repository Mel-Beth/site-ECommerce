<?php

namespace Controllers;

use Models\ProductModel;

class ArticlesController {
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
        require PROJECT_ROOT . '/src/app/Views/articles.php';
    }
}