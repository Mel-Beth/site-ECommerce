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

        // Récupération des produits
        $products = $productModel->getAllProducts(null, null, $id_categorie);

        // ✅ Ajout de la récupération des catégories
        $categories = $productModel->getCategoriesWithSubcategories();

        include('src/app/Views/admin/productsAdmin.php');
    }


    public function show($productId)
    {
        $productModel = new ProductModel();
        $product = $productModel->getProductById($productId);

        if (!$product) {
            throw new \Exception("Produit introuvable.");
        }

        // ✅ Récupérer les produits similaires
        global $similarProducts;
        $similarProducts = $productModel->getSimilarProducts($productId);

        include('src/app/Views/public/product.php');
    }

    public function publicArticles()
    {
        $productModel = new ProductModel();
        $id_categorie = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;
        $articles = $productModel->getAllProducts(null, null, $id_categorie);

        $itemsPerPage = 10;
        $page = isset($_GET['page']) && ctype_digit($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalArticles = count($articles);
        $totalPages = ceil($totalArticles / $itemsPerPage);

        $offset = ($page - 1) * $itemsPerPage;
        $paginatedArticles = array_slice($articles, $offset, $itemsPerPage);

        include('src/app/Views/public/articles.php');
    }

    public function edit($productId)
    {
        $productModel = new ProductModel();
        $product = $productModel->getProductById($productId);
        if (!$product) {
            throw new \Exception("Produit non trouvé");
        }

        $categories = $productModel->getCategoriesWithSubcategories();
        $images = $productModel->getProductImages($productId);

        include('src/app/Views/admin/edit_product.php');
    }

    public function delete($productId)
    {
        $productModel = new ProductModel();
        $productModel->deleteProduct($productId);
        header('Location: productsAdmin');
        exit();
    }

    public function add()
    {
        $productModel = new ProductModel();
        $categories = $productModel->getCategoriesWithSubcategories();
        include('src/app/Views/admin/add_product.php');
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = new ProductModel();
            $data = [
                'lib_article' => $_POST['lib_article'],
                'description' => $_POST['description'],
                'prix' => $_POST['prix'],
                'quantite_stock' => $_POST['quantite_stock'],
                'id_categorie' => $_POST['id_categorie'],
                'id_sous_categorie' => $_POST['id_sous_categorie'],
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png'];
                $fileInfo = pathinfo($_FILES['image']['name']);
                $fileExt = strtolower($fileInfo['extension']);
                if (in_array($fileExt, $allowed)) {
                    $fileName = uniqid() . '.' . $fileExt;
                    $uploadPath = 'uploads/products/' . $fileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $data['image'] = $fileName;
                    } else {
                        $_SESSION['upload_error'] = "Erreur lors de l'upload de l'image.";
                    }
                } else {
                    $_SESSION['upload_error'] = "Format de fichier non valide. Seuls les fichiers JPG, JPEG et PNG sont autorisés.";
                }
            }

            $productModel->addProduct($data);
            $_SESSION['success_message'] = "Produit ajouté avec succès !";
            header('Location: admin/productsAdmin');
            exit();
        }
    }

    public function update()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception("Méthode invalide.");
            }

            error_log("🔍 Update() appelé");

            $productModel = new ProductModel();
            $id_article = $_POST['id_article'] ?? null;

            if (!$id_article) {
                throw new \Exception("ID article manquant.");
            }

            $data = [
                'id_article' => $id_article,
                'lib_article' => $_POST['lib_article'] ?? '',
                'prix' => $_POST['prix'] ?? 0,
                'quantite_stock' => $_POST['quantite_stock'] ?? 0,
                'id_categorie' => $_POST['id_categorie'] ?? null,
                'id_sous_categorie' => $_POST['id_sous_categorie'] ?? null,
            ];

            error_log("🔍 Données à mettre à jour : " . json_encode($data));

            $success = $productModel->updateProduct($data);
            if (!$success) {
                throw new \Exception("Erreur lors de la mise à jour.");
            }

            echo json_encode(['success' => true]);
            exit();
        } catch (\Exception $e) {
            error_log("❌ Erreur update() : " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit();
        }
    }
}
