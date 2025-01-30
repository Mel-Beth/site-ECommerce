<?php

namespace Controllers;

use Models\ReviewModel;

class ReviewsController
{
    public function index()
    {
        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->getAllReviews();

        foreach ($reviews as &$review) {
            $review['reponses'] = $reviewModel->getReviewResponses($review['id_avis']);
        }

        include('src/app/Views/admin/reviews.php');
    }

    public function delete($reviewId)
    {
        $reviewModel = new ReviewModel();
        $reviewModel->deleteReview($reviewId);
        header('Location: reviews');
        exit();
    }

    public function approve($reviewId)
    {
        $reviewModel = new ReviewModel();
        $reviewModel->approveReview($reviewId);
        header('Location: reviews');
        exit();
    }
}
