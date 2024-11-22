<?php
require_once '../models/CounselorModel.php';

session_start();

class ReviewController {
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
                header("Location: ../views/counselling/counselor_profile.php?id=$counselorId");
                exit();
            } else {
                // Handle error case
                echo "Failed to add review. Please try again.";
            }
        }
    }
}

// Handle the action
if (isset($_GET['action']) && $_GET['action'] === 'addReview') {
    $controller = new ReviewController();
    $controller->addReview();
}
