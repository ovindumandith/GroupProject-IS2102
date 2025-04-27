<?php
require_once '../models/DashboardModel.php';

class DashboardController {
    private $dashboardModel;
    
    /**
     * Constructor - Initialize the Dashboard Model
     */
    public function __construct() {
        $this->dashboardModel = new DashboardModel();
    }
    
    /**
     * Get user activity data for the dashboard
     * @return array User activity data
     */
    public function getUserDashboardData() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            return [
                'error' => 'User not logged in',
                'data' => null
            ];
        }
        
        // Get user ID from session
        $userId = $_SESSION['user_id'];
        
        // Get user activity summary from model
        $activityData = $this->dashboardModel->getUserActivitySummary($userId);
        
        return [
            'error' => null,
            'data' => $activityData
        ];
    }
    
    /**
     * AJAX endpoint to fetch dashboard data
     */
    public function fetchDashboardData() {
        $result = $this->getUserDashboardData();
        
        // Set header to return JSON
        header('Content-Type: application/json');
        
        if ($result['error']) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $result['error']]);
        } else {
            echo json_encode(['success' => true, 'data' => $result['data']]);
        }
        exit;
    }
    
    /**
     * Handle different actions based on request
     */
    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'default';
        
        switch ($action) {
            case 'fetchData':
                $this->fetchDashboardData();
                break;
            default:
                // Default action - could redirect to home or dashboard page
                break;
        }
    }
}

// Only process the request if this file is accessed directly
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    $controller = new DashboardController();
    $controller->handleRequest();
}