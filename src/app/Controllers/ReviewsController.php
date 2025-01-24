<?php

namespace Controllers;

use Models\ReviewModel;

class ReviewController
{
    public function index()
    {
        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->getAllReviews();
        include('src/app/Views/admin/reviews.php');
    }

    public function delete($reviewId)
    {
        $reviewModel = new ReviewModel();
        $reviewModel->deleteReview($reviewId);
        header('Location: admin/reviews');
        exit();
    }
}