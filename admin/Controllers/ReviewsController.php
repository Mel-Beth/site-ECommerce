<?php
namespace Admin\Controllers;

use Admin\Models\ReviewModel;

class ReviewsController
{
    public function index()
    {
        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->getAllReviews();
        require __DIR__ . '/../Views/reviews.php';
    }

    public function delete($reviewId)
    {
        $reviewModel = new ReviewModel();
        $reviewModel->deleteReview($reviewId);
        header('Location: /admin/reviews');
        exit();
    }
}