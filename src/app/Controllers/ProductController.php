<?php

namespace Controllers;

use Models\ProductModel;

class ProductController
{
    public function index()
    {
        $productModel = new ProductModel();
        $products = $productModel->getAllProducts();
        include('src/app/Views/admin/products.php');
    }

    public function show($productId)
    {
        $productModel = new ProductModel();
        $product = $productModel->getProductById($productId);

        if (!$product) {
            // Gérer le cas où le produit n'existe pas
            $_SESSION['error'] = "Produit non trouvé.";
            header('Location: accueil');
            exit();
        }

        // Passer les données à la vue
        include('src/app/Views/public/product.php');
    }

    public function edit($productId)
    {
        $productModel = new ProductModel();
        $product = $productModel->getProductById($productId);
        include('src/app/Views/admin/edit_product.php');
    }
}

?>