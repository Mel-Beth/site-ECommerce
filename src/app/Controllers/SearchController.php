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

        include('src/app/Views/public/search.php');
    }
}