<?php

namespace Models;

class SearchModel extends ModeleParent
{
    public function searchProducts($query)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE lib_article LIKE :query"); // Correction ici
        $stmt->execute(['query' => '%' . $query . '%']);
        return $stmt->fetchAll();
    }
}