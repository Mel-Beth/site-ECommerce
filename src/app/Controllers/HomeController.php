<?php

namespace Controllers;

use Models\ProductModel;

class HomeController
{
    public function index()
    {
        $productModel = new ProductModel();
        try {
            $carouselImages = $productModel->getCarouselImages() ?? [];
            $categories = $productModel->getCategoriesWithSubcategories() ?? [];
            $id_categorie = isset($_GET['categorie']) && ctype_digit($_GET['categorie']) ? (int)$_GET['categorie'] : null;
            $articles = $productModel->getAllProducts(null, null, $id_categorie) ?? [];

            include('src/app/Views/public/accueil.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    public function showProduct($id)
    {
        $productModel = new ProductModel();
        $product = $productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = "Produit non trouvé.";
            header('Location: accueil');
            exit();
        }
        include('src/app/Views/public/product.php');
    }
}
