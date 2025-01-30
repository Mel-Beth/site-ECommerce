<?php

namespace Models;

use Models\ModeleParent;

class ReviewModel extends ModeleParent
{
    public function getAllReviews()
    {
        $stmt = $this->pdo->query("
        SELECT a.id_avis, a.commentaire, a.approuve, m.pseudo_membre, a.date_creation
        FROM avis a
        JOIN membres m ON a.id_membre = m.id_membre
        ORDER BY a.date_creation DESC
    ");

        $result = $stmt->fetchAll();

        return $result;
    }

    public function deleteReview($reviewId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM avis WHERE id_avis = :id");
        $stmt->execute(['id' => $reviewId]);
    }

    public function approveReview($reviewId)
    {
        $stmt = $this->pdo->prepare("UPDATE avis SET approuve = 1 WHERE id_avis = :id_avis");
        $stmt->execute(['id_avis' => $reviewId]);
    }

    public function getReviewResponses($reviewId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reponses_avis WHERE id_avis = :id_avis");
        $stmt->execute(['id_avis' => $reviewId]);
        return $stmt->fetchAll();
    }
}
