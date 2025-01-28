<?php

namespace Controllers;

use Models\ReviewModel;

class ReviewController
{
    public function index()
    {
        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->getAllReviews();

        // Récupérer les réponses aux avis
        foreach ($reviews as &$review) {
            $review['reponses'] = $reviewModel->getReviewResponses($review['id_avis']);
        }

        include('src/app/Views/admin/reviews.php');
    }

    public function delete($reviewId)
    {
        $reviewModel = new ReviewModel();
        $reviewModel->deleteReview($reviewId);
        header('Location: admin/reviews');
        exit();
    }

    public function approve($reviewId)
    {
        $reviewModel = new ReviewModel();
        $reviewModel->approveReview($reviewId);
        header('Location: admin/reviews');
        exit();
    }
}