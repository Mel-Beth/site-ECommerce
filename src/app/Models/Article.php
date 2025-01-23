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
}