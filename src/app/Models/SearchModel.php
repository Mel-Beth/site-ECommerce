<?php

namespace Models;

use Models\ModeleParent;

class SearchModel extends ModeleParent
{
    public function searchProducts($query)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE lib_article LIKE :query");
        $stmt->execute(['query' => '%' . $query . '%']);
        return $stmt->fetchAll();
    }
}