<?php
require_once '../models/AdminStressAssessmentModel.php';

class AdminStressAssessmentController {
    private $model;
    
    public function __construct() {
        $this->model = new AdminStressAssessmentModel();
    }
    
    /**
     * Handle all incoming requests
     */
    public function handleRequest() {
        // Start the session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in as admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ../views/login.php');
            exit();
        }
        
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        
        switch ($action) {
            case 'viewAllAssessments':
                $this->viewAllAssessments();
                break;
            case 'viewAssessmentDetails':
                $this->viewAssessmentDetails();
                break;
            case 'viewStudentStressTrend':
                $this->viewStudentStressTrend();
                break;
            case 'viewDashboardSummary':
                $this->viewDashboardSummary();
                break;
            default:
                // Default to viewing all assessments
                $this->viewAllAssessments();
                break;
        }
    }
    
    /**
     * View all stress assessments
     */
    public function viewAllAssessments() {
        // Get all stress assessment records
        $allAssessments = $this->model->getAllStressAssessments();
        
        // Store in session for use in view
        $_SESSION['all_assessments'] = $allAssessments;
        
        // Redirect to admin view
        include '../views/admin/admin_stress_monitoring.php';
    }
    
    /**
     * View details of a specific assessment
     */
    public function viewAssessmentDetails() {
        // Validate the assessment ID parameter
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error_message'] = "Invalid assessment ID.";
            header('Location: AdminStressAssessmentController.php?action=viewAllAssessments');
            exit();
        }
        
        $assessmentId = (int)$_GET['id'];
        
        // Get the assessment details
        $assessment = $this->model->getAssessmentById($assessmentId);
        
        if (!$assessment) {
            $_SESSION['error_message'] = "Assessment not found.";
            header('Location: AdminStressAssessmentController.php?action=viewAllAssessments');
            exit();
        }
        
        // Store data in session for view
        $_SESSION['assessment_details'] = $assessment;
        
        // Redirect to admin view
        include '../views/admin/admin_assessment_details.php';
    }
    
    /**
     * View a student's stress trend
     */
    public function viewStudentStressTrend() {
        // Validate the student ID parameter
        if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
            $_SESSION['error_message'] = "Invalid user ID.";
            header('Location: AdminStressAssessmentController.php?action=viewAllAssessments');
            exit();
        }
        
        $userId = (int)$_GET['user_id'];
        
        // Get the student's stress trend data
        $trendData = $this->model->getStudentStressTrend($userId, 10);
        
        if (!$trendData || count($trendData) < 1) {
            $_SESSION['info_message'] = "No stress assessment data available for this student.";
            header('Location: AdminStressAssessmentController.php?action=viewAllAssessments');
            exit();
        }
        
        // Get student details
        $assessment = $this->model->getAssessmentById($trendData[0]['assessment_id']);
        
        // Store the data in session for use in the view
        $_SESSION['student_name'] = $assessment['student_name'];
        $_SESSION['student_id'] = $userId;
        $_SESSION['stress_trend'] = $trendData;
        
        // Redirect to the view
        include '../views/admin/admin_student_stress_trend.php';
    }
    
    /**
     * View dashboard summary with high stress students
     */
    public function viewDashboardSummary() {
        // Get high stress students
        $highStressStudents = $this->model->getHighStressStudents(5);
        
        // Store in session for use in view
        $_SESSION['high_stress_students'] = $highStressStudents;
        
        // Redirect to admin dashboard view
        include '../views/admin/admin_dashboard.php';
    }
}

// Instantiate and run the controller if this file is accessed directly
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    $controller = new AdminStressAssessmentController();
    $controller->handleRequest();
}
?>