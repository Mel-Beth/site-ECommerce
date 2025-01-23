<?php

namespace Models;

class ProductModel extends ModeleParent
{
    public function getProductById($productId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id_article = :id_article");
        $stmt->execute(['id_article' => $productId]);
        return $stmt->fetch(); // Retourne false si le produit n'est pas trouvé
    }

    public function getAllProducts()
    {
        $stmt = $this->pdo->query("SELECT * FROM articles");
        return $stmt->fetchAll();
    }
}
