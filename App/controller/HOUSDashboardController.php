<!-- File: controller/HOUSDashboardController.php -->
<?php
require_once '../models/HOUSDashboardModel.php';

class HOUSDashboardController {
    private $model;
    
    public function __construct() {
        $this->model = new HOUSDashboardModel();
    }
    
    /**
     * Get all dashboard data
     */
    public function getDashboardData() {
        $data = [
            'counts' => $this->model->getQuestionCountsByStatus(),
            'lecturer_count' => $this->model->getLecturerCount(),
            'recent_questions' => $this->model->getRecentQuestions(5),
            'category_distribution' => $this->model->getQuestionCountsByCategory(),
            'recent_activity' => $this->model->getRecentActivity(5)
        ];
        
        return $data;
    }
    
    /**
     * Get JSON data for AJAX requests
     */
    public function getJsonData() {
        header('Content-Type: application/json');
        echo json_encode($this->getDashboardData());
    }
    
    /**
     * Load the dashboard view
     */
    public function loadDashboard() {
        // Pass data to the view via session
        $_SESSION['dashboard_data'] = $this->getDashboardData();
        
        // Redirect to the dashboard view
        header('Location: ../views/houg/houg_home.php');
        exit();
    }
}

// Handle request if this file is accessed directly
if (basename($_SERVER['PHP_SELF']) == 'HOUSDashboardController.php') {
    session_start();
    
    // Check if user is logged in as HOUS
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
        header('Location: ../login.php?error=unauthorized');
        exit();
    }
    
    $controller = new HOUSDashboardController();
    
    // Check if this is an AJAX request for refreshing data
    if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
        $controller->getJsonData();
    } else {
        $controller->loadDashboard();
    }
}
?>