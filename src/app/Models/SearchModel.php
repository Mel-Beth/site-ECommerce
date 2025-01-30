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

    public function filterResults($results, $id_categorie = null, $minPrice = null, $maxPrice = null)
    {
        return array_filter($results, function ($item) use ($id_categorie, $minPrice, $maxPrice) {
            return (!$id_categorie || $item['id_categorie'] == $id_categorie) &&
                (!$minPrice || $item['prix'] >= $minPrice) &&
                (!$maxPrice || $item['prix'] <= $maxPrice);
        });
    }

    public function searchByTags($tags)
    {
        $stmt = $this->pdo->prepare("SELECT a.* FROM articles a JOIN articles_tags at ON a.id_article = at.id_article JOIN tags t ON at.id_tag = t.id_tag WHERE t.lib_tag IN (:tags)");
        $stmt->execute(['tags' => implode(',', $tags)]);
        return $stmt->fetchAll();
    }

    public function searchProductsWithFilters($query, $id_categorie = null, $minPrice = null, $maxPrice = null)
    {
        $sql = "SELECT * FROM articles WHERE lib_article LIKE :query";
        $params = ['query' => '%' . $query . '%'];
        
        if ($id_categorie !== null) {
            $sql .= " AND id_categorie = :id_categorie";
            $params['id_categorie'] = $id_categorie;
        }
        if ($minPrice !== null) {
            $sql .= " AND prix >= :minPrice";
            $params['minPrice'] = $minPrice;
        }
        if ($maxPrice !== null) {
            $sql .= " AND prix <= :maxPrice";
            $params['maxPrice'] = $maxPrice;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}