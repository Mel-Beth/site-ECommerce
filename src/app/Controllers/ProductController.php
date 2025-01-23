<?php

namespace Controllers;

use Models\ProductModel;

class ProductController
{
    // Controllers/ProductController.php

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
        include PROJECT_ROOT . '/src/app/Views/product.php';
    }
}
