<?php

namespace Models;

class Article extends ModeleParent
{
    public function getAllArticles()
    {
        try {
            $stmt = $this->query("SELECT * FROM articles");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            throw new \Exception("Erreur lors de la récupération des articles : " . $e->getMessage());
        }
    }

    public function updateStock($id_article, $quantite)
    {
        $sql = "UPDATE articles SET quantite_stock = :quantite WHERE id_article = :id_article";
        return $this->query($sql, ['id_article' => $id_article, 'quantite' => $quantite]);
    }
}