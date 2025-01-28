<?php

namespace Controllers;

use Models\ProductModel;

class ProductController
{
    public function index()
    {
        $productModel = new ProductModel();
        $id_categorie = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;
        $sort = $_GET['sort'] ?? 'date_creation';
        $order = $_GET['order'] ?? 'DESC';
        $products = $productModel->getAllProducts(null, null, $id_categorie, $sort, $order);
        include('src/app/Views/admin/products.php');
    }

    public function show($productId)
    {
        $productModel = new ProductModel();
        $product = $productModel->getProductById($productId);

        if (!$product) {
            throw new \Exception("Produit introuvable.");
        }

        include('src/app/Views/public/product.php');
    }

    public function publicArticles()
    {
        $productModel = new ProductModel();

        // Récupération des articles
        $id_categorie = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;
        $articles = $productModel->getAllProducts(null, null, $id_categorie);

        // Gestion de la pagination
        $itemsPerPage = 10;
        $page = isset($_GET['page']) && ctype_digit($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalArticles = count($articles);
        $totalPages = ceil($totalArticles / $itemsPerPage);

        // Calcul des articles pour la page courante
        $offset = ($page - 1) * $itemsPerPage;
        $paginatedArticles = array_slice($articles, $offset, $itemsPerPage);

        // Inclure la vue avec les variables nécessaires
        include('src/app/Views/public/articles.php');
    }

    public function edit($productId)
    {
        $productModel = new ProductModel();
        $product = $productModel->getProductById($productId);
        include('src/app/Views/admin/edit_product.php');
    }

    public function delete($productId)
    {
        $productModel = new ProductModel();
        $productModel->deleteProduct($productId);
        header('Location: admin/products');
        exit();
    }
}
