<?php
namespace Admin\Models;

use Models\ModeleParent;

class ProductModel extends ModeleParent
{
    public function getOrdersByCategory()
    {
        $stmt = $this->pdo->query("
            SELECT c.lib_categorie, COUNT(co.id_commande) as commandes, SUM(a.prix * co.quantite) as revenus
            FROM contenir co
            JOIN articles a ON co.id_article = a.id_article
            JOIN categories c ON a.id_categorie = c.id_categorie
            GROUP BY c.lib_categorie
            ORDER BY commandes DESC
        ");
        return $stmt->fetchAll();
    }

    public function getAllProducts()
    {
        $stmt = $this->pdo->query("
            SELECT a.id_article, a.lib_article, a.prix, a.quantite_stock, c.lib_categorie
            FROM articles a
            LEFT JOIN categories c ON a.id_categorie = c.id_categorie
        ");
        return $stmt->fetchAll();
    }

    public function getProductById($productId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM articles WHERE id_article = :id
        ");
        $stmt->execute(['id' => $productId]);
        return $stmt->fetch();
    }
}