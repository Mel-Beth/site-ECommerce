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
        return $stmt->fetchAll();
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
            if (!isset($categories[$id_categorie])) {
                $categories[$id_categorie] = [
                    'lib_categorie' => $row['lib_categorie'],
                    'sous_categories' => [],
                ];
            }
            if (!empty($row['id_sous_categorie'])) {
                $categories[$id_categorie]['sous_categories'][] = [
                    'id_sous_categorie' => $row['id_sous_categorie'],
                    'lib_sous_categorie' => $row['lib_sous_categorie'],
                ];
            }
        }

        return $categories;
    }
}
?>
