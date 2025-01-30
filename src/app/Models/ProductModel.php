<?php

namespace Models;

use Models\ModeleParent;
use PDO;

class ProductModel extends ModeleParent
{
    public function getProductById($productId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id_article = :id_article");
        $stmt->execute(['id_article' => $productId]);
        return $stmt->fetch();
    }

    public function getAllProducts($limit = null, $offset = null, $id_categorie = null, $id_sous_categorie = null)
    {
        $sql = "SELECT a.*, 
                   c.lib_categorie, 
                   s.lib_sous_categorie 
            FROM articles a 
            LEFT JOIN categories c ON a.id_categorie = c.id_categorie 
            LEFT JOIN sous_categories s ON a.id_sous_categorie = s.id_sous_categorie
            WHERE 1";

        $params = [];

        if ($id_categorie !== null) {
            $sql .= " AND a.id_categorie = :id_categorie";
            $params['id_categorie'] = $id_categorie;
        }
        if ($id_sous_categorie !== null) {
            $sql .= " AND a.id_sous_categorie = :id_sous_categorie";
            $params['id_sous_categorie'] = $id_sous_categorie;
        }
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params['limit'] = $limit;
        }
        if ($offset !== null) {
            $sql .= " OFFSET :offset";
            $params['offset'] = $offset;
        }

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }

        $stmt->execute();
        $products = $stmt->fetchAll();

        return $products;
    }

    public function getCarouselImages()
    {
        $sql = "SELECT i.url_image AS url_image, a.lib_article FROM images i JOIN articles a ON i.id_article = a.id_article LIMIT 5";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getCategoriesWithSubcategories()
    {
        $sql = "SELECT c.id_categorie, c.lib_categorie, s.id_sous_categorie, s.lib_sous_categorie
            FROM categories c
            LEFT JOIN sous_categories s ON c.id_categorie = s.id_categorie
            ORDER BY c.id_categorie, s.id_sous_categorie";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rows as $row) {
            $id_categorie = $row['id_categorie'];

            if (!isset($categories[$id_categorie])) {
                $categories[$id_categorie] = [
                    'id_categorie' => $id_categorie,
                    'lib_categorie' => $row['lib_categorie'],
                    'sous_categories' => []
                ];
            }

            if (!empty($row['id_sous_categorie'])) {
                $categories[$id_categorie]['sous_categories'][] = [
                    'id_sous_categorie' => $row['id_sous_categorie'],
                    'lib_sous_categorie' => $row['lib_sous_categorie']
                ];
            }
        }

        return array_values($categories);
    }


    public function getAllProductsForAdmin($limit = null, $offset = null, $id_categorie = null, $id_sous_categorie = null)
    {
        $sql = "SELECT a.id_article, a.lib_article, a.prix, a.quantite_stock, 
                   c.lib_categorie 
            FROM articles a
            LEFT JOIN categories c ON a.id_categorie = c.id_categorie
            WHERE 1";

        $params = [];

        if ($id_categorie !== null) {
            $sql .= " AND a.id_categorie = :id_categorie";
            $params['id_categorie'] = $id_categorie;
        }
        if ($id_sous_categorie !== null) {
            $sql .= " AND a.id_sous_categorie = :id_sous_categorie";
            $params['id_sous_categorie'] = $id_sous_categorie;
        }
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params['limit'] = $limit;
        }
        if ($offset !== null) {
            $sql .= " OFFSET :offset";
            $params['offset'] = $offset;
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        print_r($products); // DEBUG: Vérifie si les produits ont une catégorie

        return $products;
    }

    public function addProduct($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO articles (lib_article, description, prix, quantite_stock, id_categorie, id_sous_categorie) VALUES (:lib_article, :description, :prix, :quantite_stock, :id_categorie, :id_sous_categorie)");
        return $stmt->execute($data);
    }

    public function deleteProduct($productId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id_article = :id_article");
        return $stmt->execute(['id_article' => $productId]);
    }

    public function getProductImages($productId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM images WHERE id_article = :id_article");
        $stmt->execute(['id_article' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateProduct($data)
    {
        $sql = "UPDATE articles SET 
                lib_article = :lib_article, 
                prix = :prix, 
                quantite_stock = :quantite_stock, 
                id_categorie = :id_categorie, 
                id_sous_categorie = :id_sous_categorie 
            WHERE id_article = :id_article";

        $stmt = $this->pdo->prepare($sql);

        error_log("🔍 Requête SQL : " . $sql);
        error_log("🔍 Valeurs : " . json_encode($data));

        $success = $stmt->execute($data);

        if (!$success) {
            error_log("❌ Erreur SQL : " . json_encode($stmt->errorInfo()));
        }

        return $success;
    }

    public function getSimilarProducts($productId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM articles 
            WHERE id_categorie = (
                SELECT id_categorie FROM articles WHERE id_article = :id_article
            ) 
            AND id_article != :id_article
            LIMIT 5
        ");
        $stmt->execute(['id_article' => $productId]);
        return $stmt->fetchAll();
    }

    public function applyDiscount($productId, $discountPercentage)
    {
        $pdo = $this->getPdo();

        $stmt = $pdo->prepare("UPDATE articles SET prix = prix - (prix * :discount / 100) WHERE id_article = :id");
        $stmt->execute([
            'discount' => $discountPercentage,
            'id' => $productId
        ]);
    }

    public function updateStock($productId, $quantitySold)
    {
        $pdo = $this->getPdo();

        $stmt = $pdo->prepare("UPDATE articles SET quantite_stock = quantite_stock - :quantity WHERE id_article = :id");
        $stmt->execute([
            'quantity' => $quantitySold,
            'id' => $productId
        ]);
    }
}
