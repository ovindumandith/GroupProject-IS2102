<?php
require_once '../models/CounselorDashboardModel.php';

class CounselorDashboardController {
    private $dashboardModel;
    
    /**
     * Constructor - Initialize the Counselor Dashboard Model
     */
    public function __construct() {
        $this->dashboardModel = new CounselorDashboardModel();
    }
    
    /**
     * Get all dashboard metrics for a counselor
     * @param int $counselorId The counselor ID
     * @return array All dashboard metrics
     */
    public function getDashboardMetrics($counselorId) {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is a counselor
        if (!isset($_SESSION['counselor']) || !isset($_SESSION['counselor']['id'])) {
            return [
                'error' => 'Unauthorized access',
                'data' => null
            ];
        }
        
        // Get all dashboard metrics from model
        $metrics = $this->dashboardModel->getAllDashboardMetrics($counselorId);
        
        return [
            'error' => null,
            'data' => $metrics
        ];
    }
    
    /**
     * AJAX endpoint to fetch dashboard metrics
     */
    public function fetchDashboardData() {
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
        $result = $this->getDashboardMetrics($counselorId);
        
        // Set header to return JSON
        header('Content-Type: application/json');
        
        if ($result['error']) {
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'message' => $result['error']]);
        } else {
            echo json_encode(['success' => true, 'data' => $result['data']]);
        }
        exit;
    }
    
    /**
     * Load the counselor dashboard page
     */
    public function loadDashboard() {
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
        
        // Get all dashboard metrics
        $result = $this->getDashboardMetrics($counselorId);
        $dashboardData = $result['data'];
        
        // Include the counselor dashboard view
        include_once '../views/counselling/counselor_dashboard.php';
    }
    
    /**
     * Handle different actions based on request
     */
    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'loadDashboard';
        
        switch ($action) {
            case 'fetchData':
                $this->fetchDashboardData();
                break;
            case 'loadDashboard':
            default:
                $this->loadDashboard();
                break;
        }
    }
}

// Only process the request if this file is accessed directly
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    $controller = new CounselorDashboardController();
    $controller->handleRequest();
}