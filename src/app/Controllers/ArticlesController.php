<?php

namespace Controllers;

use Models\ProductModel;

class ArticlesController
{
    public function index()
    {
        $productModel = new ProductModel();

        try {
            $id_categorie = isset($_GET['categorie']) && ctype_digit($_GET['categorie']) ? (int)$_GET['categorie'] : null;
            $sort = isset($_GET['sort']) && in_array($_GET['sort'], ['date_creation', 'prix']) ? $_GET['sort'] : 'date_creation';
            $order = isset($_GET['order']) && in_array($_GET['order'], ['ASC', 'DESC']) ? $_GET['order'] : 'DESC';

            $articles = $productModel->getAllProducts(null, null, $id_categorie, $sort, $order);

            include('src/app/Views/public/articles.php');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}