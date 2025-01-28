<?php

namespace Models;

use Models\ModeleParent;

class ProductModel extends ModeleParent
{
    public function getProductById($productId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id_article = :id_article");
        $stmt->execute(['id_article' => $productId]);
        return $stmt->fetch();
    }

    public function getAllProducts($limit = null, $offset = null, $id_categorie = null)
    {
        $sql = "
        SELECT a.*, i.url_image
        FROM articles a
        LEFT JOIN images i ON a.id_article = i.id_article
    ";
        if ($id_categorie !== null) {
            $sql .= " WHERE a.id_categorie = :id_categorie";
        }
        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($id_categorie !== null) {
            $stmt->bindValue(':id_categorie', $id_categorie, \PDO::PARAM_INT);
        }
        if ($limit !== null && $offset !== null) {
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalProducts($id_categorie = null)
    {
        $sql = "SELECT COUNT(*) as total FROM articles";
        if ($id_categorie !== null) {
            $sql .= " WHERE id_categorie = :id_categorie";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($id_categorie !== null) {
            $stmt->bindValue(':id_categorie', $id_categorie, \PDO::PARAM_INT);
        }
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    public function getCarouselImages()
    {
        $sql = "SELECT url_image, alt FROM images LIMIT 3"; // Limite à 3 images pour le carrousel
        return $this->query($sql)->fetchAll();
    }

    public function getCategoriesWithSubcategories()
    {
        $sql = "
        SELECT c.id_categorie, c.lib_categorie, sc.id_sous_categorie, sc.lib_sous_categorie, sc.description
        FROM categories c
        LEFT JOIN sous_categories sc ON c.id_categorie = sc.id_categorie
    ";
        $results = $this->query($sql)->fetchAll();

        // Organiser les résultats en un tableau structuré
        $categories = [];
        foreach ($results as $row) {
            $id_categorie = $row['id_categorie'];
            if (!isset($categories[$id_categorie])) {
                $categories[$id_categorie] = [
                    'lib_categorie' => $row['lib_categorie'],
                    'sous_categories' => [],
                ];
            }
            if ($row['id_sous_categorie']) {
                $categories[$id_categorie]['sous_categories'][] = [
                    'lib_sous_categorie' => $row['lib_sous_categorie'],
                    'image' => 'assets/images/' . strtolower(str_replace(' ', '_', $row['lib_sous_categorie'])) . '.webp', // Exemple de chemin d'image
                ];
            }
        }

        return array_values($categories);
    }

    public function getProductImages($productId)
    {
        $sql = "SELECT * FROM images WHERE id_article = :id_article";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_article' => $productId]);
        return $stmt->fetchAll();
    }

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

    public function getProductsByCategory($id_categorie)
    {
        $sql = "SELECT * FROM articles WHERE id_categorie = :id_categorie";
        return $this->query($sql, ['id_categorie' => $id_categorie])->fetchAll();
    }
}
