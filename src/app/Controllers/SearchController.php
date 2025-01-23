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

        include PROJECT_ROOT . '/src/app/Views/search.php';
    }
}