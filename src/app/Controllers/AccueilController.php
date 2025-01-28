<?php

namespace Controllers;

use Models\ProductModel;

class AccueilController
{
    public function index()
    {
        $productModel = new ProductModel();

        try {
            // Récupérer les images du carrousel depuis la base de données
            $carouselImages = $productModel->getCarouselImages() ?? [];

            // Récupérer les catégories et les sous-catégories depuis la base de données
            $categories = $productModel->getCategoriesWithSubcategories() ?? [];

            // Récupérer tous les articles sans pagination (pour gestion via JS)
            $id_categorie = isset($_GET['categorie']) && ctype_digit($_GET['categorie']) ? (int)$_GET['categorie'] : null;
            $articles = $productModel->getAllProducts(null, null, $id_categorie) ?? [];

            // Passer les données à la vue
            include('src/app/Views/public/accueil.php');
        } catch (\Exception $e) {
            // Journaliser l'erreur pour le développeur
            error_log($e->getMessage());
            // Afficher un message d'erreur pour l'utilisateur
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    
}

?>