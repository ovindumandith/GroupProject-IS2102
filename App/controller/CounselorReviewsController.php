<?php
require_once '../models/CounselorReviewsModel.php';

class CounselorReviewsController {
    private $reviewsModel;
    
    /**
     * Constructor - Initialize the Counselor Reviews Model
     */
    public function __construct() {
        $this->reviewsModel = new CounselorReviewsModel();
    }
    
    /**
     * Load the reviews page
     */
    public function viewReviews() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is a counselor
        if (!isset($_SESSION['counselor']) || !isset($_SESSION['counselor']['id'])) {
            header('Location: ../views/counselling/counselor_login.php');
            exit();
        }
        
        $counselorId = $_SESSION['counselor']['id'];
        
        // Get page parameters for pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Number of reviews per page
        $offset = ($page - 1) * $limit;
        
        // Get reviews and statistics
        $reviews = $this->reviewsModel->getCounselorReviews($counselorId, $limit, $offset);
        $stats = $this->reviewsModel->getReviewStatistics($counselorId);
        
        // Calculate total pages for pagination
        $totalReviews = $stats['total_reviews'];
        $totalPages = ceil($totalReviews / $limit);
        
        // Include the reviews view
        include_once '../views/counselling/reviews.php';
    }
    
    /**
     * AJAX endpoint to fetch more reviews
     */
    public function fetchMoreReviews() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is a counselor
        if (!isset($_SESSION['counselor']) || !isset($_SESSION['counselor']['id'])) {
            header('Content-Type: application/json');
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            exit;
        }
        
        $counselorId = $_SESSION['counselor']['id'];
        
        // Get page parameters
        $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;
        $offset = ($page - 1) * $limit;
        
        // Get reviews
        $reviews = $this->reviewsModel->getCounselorReviews($counselorId, $limit, $offset);
        
        // Set header to return JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $reviews]);
        exit;
    }
    
    /**
     * Handle different actions based on request
     */
    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'viewReviews';
        
        switch ($action) {
            case 'fetchMore':
                $this->fetchMoreReviews();
                break;
            case 'viewReviews':
            default:
                $this->viewReviews();
                break;
        }
    }
}

// Only process the request if this file is accessed directly
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    $controller = new CounselorReviewsController();
    $controller->handleRequest();
}