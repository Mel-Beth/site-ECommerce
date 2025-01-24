<?php

namespace Controllers;

use Models\ProductModel;

class ArticlesController
{
    public function index()
    {
        // Charger le modèle ProductModel
        $productModel = new ProductModel();

        // Récupérer les articles
        try {
            $articles = $productModel->getAllProducts();
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        // Passer les données à la vue
        include('src/app/Views/public/articles.php');
    }
}