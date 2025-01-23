<?php
namespace Admin\Models;

use Models\ModeleParent;

class ReviewModel extends ModeleParent
{
    public function getAllReviews()
    {
        $stmt = $this->pdo->query("
            SELECT a.id_avis, a.commentaire, m.pseudo_membre, a.date_creation
            FROM avis a
            JOIN membres m ON a.id_membre = m.id_membre
        ");
        return $stmt->fetchAll();
    }

    public function deleteReview($reviewId)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM avis WHERE id_avis = :id
        ");
        $stmt->execute(['id' => $reviewId]);
    }
}