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

    public function getAllProducts($limit = null, $offset = null, $id_categorie = null, $id_sous_categorie = null)
    {
        $sql = "SELECT * FROM articles WHERE 1";
        $params = [];
        if ($id_categorie !== null) {
            $sql .= " AND id_categorie = :id_categorie";
            $params['id_categorie'] = $id_categorie;
        }
        if ($id_sous_categorie !== null) {
            $sql .= " AND id_sous_categorie = :id_sous_categorie";
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

        // Ajoute une vérification de la catégorie avant de retourner le produit
        foreach ($products as &$product) {
            if (!$product['id_categorie']) {
                $product['lib_categorie'] = 'Aucune catégorie';
            } else {
                $categoryStmt = $this->pdo->prepare("SELECT lib_categorie FROM categories WHERE id_categorie = :id_categorie");
                $categoryStmt->execute(['id_categorie' => $product['id_categorie']]);
                $category = $categoryStmt->fetch();
                $product['lib_categorie'] = $category['lib_categorie'] ?? 'Aucune catégorie';
            }
        }

        return $products;
    }


    public function getCarouselImages()
    {
        $sql = "SELECT i.url_image AS url_image, a.lib_article 
                FROM images i 
                JOIN articles a ON i.id_article = a.id_article 
                LIMIT 5";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getCategoriesWithSubcategories()
    {
        $sql = "
        SELECT c.id_categorie, c.lib_categorie, sc.id_sous_categorie, sc.lib_sous_categorie
        FROM categories c
        LEFT JOIN sous_categories sc ON c.id_categorie = sc.id_categorie
    ";
        $stmt = $this->pdo->query($sql);
        $results = $stmt->fetchAll();

        $categories = [];

        foreach ($results as $row) {
            $id_categorie = $row['id_categorie'];

            // Initialiser la catégorie si elle n'existe pas déjà
            if (!isset($categories[$id_categorie])) {
                $categories[$id_categorie] = [
                    'lib_categorie' => $row['lib_categorie'],
                    'sous_categories' => []
                ];
            }

            // Ajouter la sous-catégorie si elle existe
            if (!empty($row['id_sous_categorie'])) {
                $categories[$id_categorie]['sous_categories'][] = [
                    'id_sous_categorie' => $row['id_sous_categorie'],
                    'lib_sous_categorie' => $row['lib_sous_categorie']
                ];
            }
        }

        return $categories;
    }


    public function getAllProductsForAdmin($limit = null, $offset = null, $id_categorie = null, $id_sous_categorie = null)
    {
        // Requête spécifique à l'admin pour récupérer les produits avec les catégories
        $sql = "
        SELECT a.id_article, a.lib_article, a.prix, a.taux_promotion, c.lib_categorie
        FROM articles a
        LEFT JOIN categories c ON a.id_categorie = c.id_categorie
        WHERE 1
    ";
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
        return $stmt->fetchAll();
    }

    public function addProduct($data)
    {
        // Préparer la requête d'insertion
        $stmt = $this->pdo->prepare("
            INSERT INTO articles (lib_article, description, prix, quantite_stock, id_categorie, id_sous_categorie)
            VALUES (:lib_article, :description, :prix, :quantite_stock, :id_categorie, :id_sous_categorie)
        ");
        $stmt->execute([
            'lib_article' => $data['lib_article'],
            'description' => $data['description'],
            'prix' => $data['prix'],
            'quantite_stock' => $data['quantite_stock'],
            'id_categorie' => $data['id_categorie'],
            'id_sous_categorie' => $data['id_sous_categorie']
        ]);
    }

    public function deleteProduct($productId)
    {
        // Préparer la requête de suppression
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id_article = :id_article");
        $stmt->execute(['id_article' => $productId]);
    }
}
