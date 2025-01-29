<?php

namespace Controllers;

use Models\ProductModel;

class ProductsController
{
    public function index()
    {
        $productModel = new ProductModel();
        $id_categorie = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;
        $sort = $_GET['sort'] ?? 'date_creation';
        $order = $_GET['order'] ?? 'DESC';

        // Utilisation de la méthode existante 'getAllProducts'
        $products = $productModel->getAllProducts(null, null, $id_categorie);

        include('src/app/Views/admin/productsAdmin.php');
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
        if (!$product) {
            throw new \Exception("Produit non trouvé");
        }

        // Récupérer les catégories
        $categories = $productModel->getCategoriesWithSubcategories();

        include('src/app/Views/admin/edit_product.php');
    }

    public function delete($productId)
    {
        $productModel = new ProductModel();
        $productModel->deleteProduct($productId);
        header('Location: admin/productsAdmin');
        exit();
    }

    public function add()
    {
        $productModel = new ProductModel();

        // Récupérer les catégories pour l'ajout
        $categories = $productModel->getCategoriesWithSubcategories();

        // Passer les catégories à la vue
        include('src/app/Views/admin/add_product.php');
    }


    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new ProductModel();

            // Récupérer les données du formulaire
            $data = [
                'lib_article' => $_POST['lib_article'],
                'description' => $_POST['description'],
                'prix' => $_POST['prix'],
                'quantite_stock' => $_POST['quantite_stock'],
                'id_categorie' => $_POST['id_categorie'],
                'id_sous_categorie' => $_POST['id_sous_categorie'],
            ];

            $productModel->addProduct($data);

            // Rediriger vers la gestion des produits
            header('Location: admin/productsAdmin');
            exit();
        }
    }
}
