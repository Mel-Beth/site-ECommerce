<?php

namespace Controllers;

use Models\SearchModel;

class SearchController
{
    public function index()
    {
        $query = $_GET['q'] ?? '';
        $searchModel = new SearchModel();
        $results = $searchModel->searchProducts($query);

        // Ajout de filtres de recherche
        $id_categorie = isset($_GET['categorie']) && ctype_digit($_GET['categorie']) ? (int)$_GET['categorie'] : null;
        $minPrice = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (float)$_GET['min_price'] : null;
        $maxPrice = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : null;

        if ($id_categorie || $minPrice || $maxPrice) {
            $results = $searchModel->filterResults($results, $id_categorie, $minPrice, $maxPrice);
        }

        include('src/app/Views/public/search.php');
    }
}