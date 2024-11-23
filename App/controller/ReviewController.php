<?php
require_once '../models/CounselorModel.php';
require_once '../models/ReviewModel.php';

session_start();

class ReviewController {

    private $reviewModel;

    public function __construct() {
        $this->reviewModel = new ReviewModel();
    }
    public function addReview() {
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/login.php');
            exit();
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $counselorId = $_POST['counselor_id'];
            $userId = $_SESSION['user_id'];
            $rating = $_POST['rating'];
            $reviewText = $_POST['review_text'];

            $counselorModel = new CounselorModel();

            // Add the review to the database
            $success = $counselorModel->addReview($counselorId, $userId, $rating, $reviewText);

            if ($success) {
                // Redirect to the counselor's profile page
                header("Location: ../../App/views/counselling/counsellor_profile.php?id=$counselorId");
                exit();
            } else {
                // Handle error case
                echo "Failed to add review. Please try again.";
            }
        }
    }
        public function updateReview() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reviewId = $_POST['review_id'];
            $userId = $_SESSION['user_id'];
            $rating = $_POST['rating'];
            $reviewText = $_POST['review_text'];

            if ($this->reviewModel->isUserReviewOwner($reviewId, $userId)) {
                if ($this->reviewModel->updateReview($reviewId, $userId, $rating, $reviewText)) {
                    header("Location: counselor_profile.php?id=" . $_POST['counselor_id']);
                    exit();
                } else {
                    echo "Failed to update review.";
                }
            } else {
                echo "You are not authorized to update this review.";
            }
        }
    }
    public function deleteReview() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reviewId = $_POST['review_id'];
            $userId = $_SESSION['user_id'];

            if ($this->reviewModel->isUserReviewOwner($reviewId, $userId)) {
                if ($this->reviewModel->deleteReview($reviewId, $userId)) {
                    header("Location: ../views/counselling/counsellor_profile.php?id=" . $_POST['counselor_id']);
                    exit();
                } else {
                    echo "Failed to delete review.";
                }
            } else {
                echo "You are not authorized to delete this review.";
            }
        }
    }
}
    


// Handle the action
if (isset($_GET['action'])) {
    $controller = new ReviewController();
    if ($_GET['action'] === 'addReview') {
        $controller->addReview();
    } elseif ($_GET['action'] === 'deleteReview') {
        $controller->deleteReview();
    } elseif ($_GET['action'] === 'updateReview') { // Add this case for updating a review
        $controller->updateReview();
    }
}

