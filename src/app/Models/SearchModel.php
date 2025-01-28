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
        $sql = "SELECT a.* FROM articles a JOIN articles_tags at ON a.id_article = at.id_article JOIN tags t ON at.id_tag = t.id_tag WHERE t.lib_tag IN (:tags)";
        return $this->query($sql, ['tags' => $tags])->fetchAll();
    }
}
