<?php
require_once '../models/AdminDashboardModel.php';

class AdminDashboardController {
    private $dashboardModel;
    
    /**
     * Constructor - Initialize the Admin Dashboard Model
     */
    public function __construct() {
        $this->dashboardModel = new AdminDashboardModel();
    }
    
    /**
     * Get all dashboard metrics for admin
     * @return array All dashboard metrics
     */
    public function getDashboardMetrics() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            return [
                'error' => 'Unauthorized access',
                'data' => null
            ];
        }
        
        // Get all dashboard metrics from model
        $metrics = $this->dashboardModel->getAllDashboardMetrics();
        
        return [
            'error' => null,
            'data' => $metrics
        ];
    }
    
    /**
     * AJAX endpoint to fetch dashboard metrics
     */
    public function fetchDashboardData() {
        $result = $this->getDashboardMetrics();
        
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
     * Load the admin dashboard page
     */
    public function loadDashboard() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ../views/login.php');
            exit();
        }
        
        // Get all dashboard metrics
        $result = $this->getDashboardMetrics();
        $dashboardData = $result['data'];
        
        // Include the admin dashboard view
        include_once '../views/admin_home.php';
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
    $controller = new AdminDashboardController();
    $controller->handleRequest();
}