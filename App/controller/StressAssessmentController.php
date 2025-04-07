<?php
session_start(); // Start the session to access user data
require_once '../models/StressAssessmentModel.php';

class StressAssessmentController {
    private $model;
    
    public function __construct() {
        $this->model = new StressAssessmentModel();
    }
    
    /**
     * Handle all incoming requests
     */
    public function handleRequest() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/login.php');
            exit();
        }
        
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        
        switch ($action) {
            case 'submit_assessment':
                $this->submitAssessment();
                break;
            case 'view_records':
                $this->viewRecords();
                break;
            case 'view_details':
                $this->viewAssessmentDetails();
                break;
            case 'view_trend':
                $this->viewStressTrend();
                break;
            case 'view_all_assessments':
                $this->viewAllAssessments();
                break;
            default:
                // If no action specified, show the assessment form
                header('Location: ../views/stress_management/stress_management_form.php');
                break;
        }
    }
    
    /**
     * Handle assessment form submission
     */
    private function submitAssessment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            
            // Validate and collect all responses
            $responses = [
                'section1_q1' => $this->validateInput($_POST['section1_q1'] ?? null, 0, 4),
                'section1_q2' => $this->validateInput($_POST['section1_q2'] ?? null, 0, 4),
                'section1_q3' => $this->validateInput($_POST['section1_q3'] ?? null, 0, 4),
                'section1_q4' => $this->validateInput($_POST['section1_q4'] ?? null, 0, 4),
                'section1_q5' => $this->validateInput($_POST['section1_q5'] ?? null, 0, 4),
                'section2_q1' => $this->validateInput($_POST['section2_q1'] ?? null, 0, 4),
                'section2_q2' => $this->validateInput($_POST['section2_q2'] ?? null, 0, 4),
                'section2_q3' => $this->validateInput($_POST['section2_q3'] ?? null, 0, 4),
                'section2_q4' => $this->validateInput($_POST['section2_q4'] ?? null, 0, 4),
                'section2_q5' => $this->validateInput($_POST['section2_q5'] ?? null, 0, 4)
            ];
            
            // Check if all responses are valid
            foreach ($responses as $response) {
                if ($response === false) {
                    $_SESSION['error_message'] = "Please provide valid responses to all questions.";
                    header('Location: ../views/stress_management/stress_management_form.php');
                    exit();
                }
            }
            
            // Save the assessment
            $result = $this->model->saveStressAssessment($userId, $responses);
            
            if ($result) {
                $_SESSION['success_message'] = "Your stress assessment has been submitted successfully.";
                header('Location: ../views/stress_management/assessment_results.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Failed to save your assessment. Please try again.";
                header('Location: ../views/stress_management/stress_management_form.php');
                exit();
            }
        } else {
            header('Location: ../views/stress_management/stress_management_form.php');
            exit();
        }
    }
    
    /**
     * Validate input to ensure it's within the specified range
     * 
     * @param mixed $input The input to validate
     * @param int $min Minimum valid value
     * @param int $max Maximum valid value
     * @return int|bool The validated input or false if invalid
     */
    private function validateInput($input, $min, $max) {
        if ($input === null || $input === '') {
            return false;
        }
        
        $value = (int) $input;
        if ($value < $min || $value > $max) {
            return false;
        }
        
        return $value;
    }
    
    /**
     * Retrieve and display user's assessment records
     */
    private function viewRecords() {
        $userId = $_SESSION['user_id'];
        $records = $this->model->getStressAssessmentRecords($userId);
        
        if ($records) {
            $_SESSION['assessment_records'] = $records;
        } else {
            $_SESSION['info_message'] = "No assessment records found.";
        }
        
        header('Location: ../views/stress_management/assessment_history.php');
        exit();
    }
    
    /**
     * View details of a specific assessment
     */
    private function viewAssessmentDetails() {
        if (isset($_GET['id'])) {
            $assessmentId = (int) $_GET['id'];
            $assessment = $this->model->getAssessmentById($assessmentId);
            
            if ($assessment && $assessment['user_id'] == $_SESSION['user_id']) {
                $_SESSION['assessment_details'] = $assessment;
                
                // Get recommended techniques based on stress level
                $_SESSION['recommended_techniques'] = $this->model->getRecommendedTechniques($assessment['stress_level']);
                
                header('Location: ../views/stress_management/assessment_details.php');
                exit();
            }
        }
        
        $_SESSION['error_message'] = "Assessment not found or access denied.";
        header('Location: ../views/stress_management/assessment_history.php');
        exit();
    }
    
    /**
     * View stress trend over time
     */
    private function viewStressTrend() {
        $userId = $_SESSION['user_id'];
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
        $trend = $this->model->getStressTrend($userId, $limit);
        
        if ($trend) {
            $_SESSION['stress_trend'] = $trend;
        } else {
            $_SESSION['info_message'] = "Not enough data to show stress trend.";
        }
        
        header('Location: ../views/stress_management/stress_trend.php');
        exit();
    }
    
    /**
     * View all stress assessments (Admin function)
     */
    public function viewAllAssessments() {
        session_start();
        
        // Check if user is logged in as admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/admin/admin_login.php');
            exit();
        }
        
        // Get all stress assessment records
        $allAssessments=$this->model->getAllStressAssessments();
        
        // Store in session for use in view
        $_SESSION['all_assessments'] = $allAssessments;
        
        // Redirect to admin view
        include '../views/admin/admin_stress_monitoring.php';
    }

    
}

// Instantiate and run the controller
$controller = new StressAssessmentController();
$controller->handleRequest();
?>
