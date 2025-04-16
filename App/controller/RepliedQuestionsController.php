<?php
session_start();
require_once '../models/RepliedQuestionsModel.php';

class RepliedQuestionsController {
    private $model;
    
    public function __construct() {
        $this->model = new RepliedQuestionsModel();
    }
    
    /**
     * Main controller function to handle different actions
     */
    public function handleRequest() {
        // Check if action parameter exists
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            
            // Handle different actions
            switch ($action) {
                case 'viewRepliedQuestions':
                    $this->viewRepliedQuestions();
                    break;
                case 'viewReplyDetails':
                    $this->viewReplyDetails();
                    break;
                case 'getLecturerReplies':
                    $this->getLecturerReplies();
                    break;
                case 'updateReply':
                    $this->updateReply();
                    break;
                case 'deleteReply':
                    $this->deleteReply();
                    break;    
                default:
                    // Redirect to dashboard if action is not recognized
                    header('Location: ../views/home.php');
                    exit();
            }
        } else {
            // Default action is to view replied questions
            $this->viewRepliedQuestions();
        }
    }
    
    /**
     * View all replied questions based on user role
     */
    private function viewRepliedQuestions() {
        // Check user role to determine which questions to show
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        $role = $_SESSION['role'];
        $userId = $_SESSION['user_id'];
        
        // Get questions based on role
        if ($role === 'hous') {
            // Head of undergraduate studies can see all replied questions
            $repliedQuestions = $this->model->getAllRepliedQuestions();
        } elseif ($role === 'lecturer') {
            // Lecturers can see questions they've replied to
            $repliedQuestions = $this->model->getRepliedQuestionsByLecturer($userId);
        } elseif ($role === 'student') {
            // Students can see their own replied questions
            // Assuming student_id is stored in session or can be retrieved
            if (isset($_SESSION['student_id'])) {
                $studentId = $_SESSION['student_id'];
                $repliedQuestions = $this->model->getRepliedQuestionsForStudent($studentId);
            } else {
                $_SESSION['error_message'] = "Student ID not found. Please contact support.";
                header('Location: ../views/home.php');
                exit();
            }
        } else {
            // Other roles not allowed to view replied questions
            $_SESSION['error_message'] = "You don't have permission to view this page.";
            header('Location: ../views/houg/houg_home.php');
            exit();
        }
        
        // Store questions in session for view to access
        $_SESSION['replied_questions'] = $repliedQuestions;
        
        // Load appropriate view based on role
        if ($role === 'hous') {
            header('Location: ../views/houg/forwarded_replied_questions.php');
        } elseif ($role === 'lecturer') {
            header('Location: ../views/lecturer/replied_question.php');
        } elseif ($role === 'student') {
            header('Location: ../views/student/replied_questions.php');
        }
        exit();
    }
    
    /**
     * View details of a specific reply
     */
    private function viewReplyDetails() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error_message'] = "Invalid reply ID.";
            header('Location: ../views/home.php');
            exit();
        }
        
        $replyId = $_GET['id'];
        $replyDetails = $this->model->getReplyById($replyId);
        
        if (!$replyDetails) {
            $_SESSION['error_message'] = "Reply not found.";
            header('Location: ../views/home.php');
            exit();
        }
        
        // Store reply details in session for view to access
        $_SESSION['reply_details'] = $replyDetails;
        
        // Determine which view to load based on user role
        $role = $_SESSION['role'];
        if ($role === 'hous') {
            header('Location: ../views/houg/reply_details.php');
        } elseif ($role === 'lecturer') {
            header('Location: ../views/lecturer/reply_details.php');
        } elseif ($role === 'student') {
            header('Location: ../views/student/reply_details.php');
        } else {
            header('Location: ../views/home.php');
        }
        exit();
    }
    
    /**
     * Get all replied questions by a specific lecturer
     * Used via AJAX to update the dashboard
     */
    private function getLecturerReplies() {
        // Check if user is logged in as lecturer
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
            echo json_encode(['error' => 'Unauthorized access']);
            exit();
        }
        
        $lecturerId = $_SESSION['user_id'];
        $repliedQuestions = $this->model->getRepliedQuestionsByLecturer($lecturerId);
        
        // Return as JSON for AJAX handling
        header('Content-Type: application/json');
        echo json_encode($repliedQuestions);
        exit();
    }
    
    /**
     * Update a reply
     */
private function updateReply() {
    // Check if user is logged in as lecturer
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
        header('Location: ../login.php?error=unauthorized');
        exit();
    }
    
    // Debug logging
    error_log("Update Reply - POST data: " . print_r($_POST, true));
    
    // Check if all required parameters are set
    if (!isset($_POST['reply_id']) || !isset($_POST['reply_text'])) {
        $_SESSION['error_message'] = "Missing required information.";
        header('Location: ../controller/RepliedQuestionsController.php?action=viewRepliedQuestions');
        exit();
    }
    
    $replyId = (int)$_POST['reply_id'];
    $replyText = $_POST['reply_text'];
    $lecturerId = $_SESSION['user_id'];
    
    // Add debug logging
    error_log("Attempting to update reply ID: $replyId by lecturer ID: $lecturerId");
    error_log("New reply text: $replyText");
    
    // Update the reply
    $result = $this->model->updateReply($replyId, $replyText, $lecturerId);
    
    if ($result) {
        $_SESSION['success_message'] = "Your reply has been updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update reply. Please try again.";
        error_log("Failed to update reply ID: $replyId by lecturer ID: $lecturerId");
    }
    
    // Redirect back to replied questions list
    header('Location: ../controller/RepliedQuestionsController.php?action=viewRepliedQuestions');
    exit();
}
    
    /**
     * Delete a reply
     */
private function deleteReply() {
    // Check if user is logged in as lecturer
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
        header('Location: ../login.php?error=unauthorized');
        exit();
    }
    
    // Debug POST data
    error_log("DELETE REQUEST - POST data: " . print_r($_POST, true));
    
    // Check if reply ID is provided and valid
    if (!isset($_POST['reply_id']) || empty($_POST['reply_id']) || !is_numeric($_POST['reply_id'])) {
        $_SESSION['error_message'] = "Invalid or missing reply ID: " . (isset($_POST['reply_id']) ? $_POST['reply_id'] : 'not set');
        header('Location: ../controller/RepliedQuestionsController.php?action=viewRepliedQuestions');
        exit();
    }
    
    $replyId = (int)$_POST['reply_id'];
    $lecturerId = $_SESSION['user_id'];
    
    // Delete the reply
    $result = $this->model->deleteReply($replyId, $lecturerId);
    
    if ($result) {
        $_SESSION['success_message'] = "Your reply has been deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete reply. Please try again. (Reply ID: $replyId)";
    }
    
    // Redirect back to replied questions list
    header('Location: ../controller/RepliedQuestionsController.php?action=viewRepliedQuestions');
    exit();
}
}

// Create controller instance and handle request
$controller = new RepliedQuestionsController();
$controller->handleRequest();
?>