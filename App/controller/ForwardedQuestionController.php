<?php
session_start();
require_once '../models/ForwardedQuestionModel.php';
require_once '../models/RepliedQuestionsModel.php';

class ForwardedQuestionController {
    private $model;
    private $repliedModel;
    
    public function __construct() {
        $this->model = new ForwardedQuestionModel();
        $this->repliedModel = new RepliedQuestionsModel();
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
                case 'viewForwardedQuestions':
                    $this->viewForwardedQuestions();
                    break;
                case 'viewQuestion':
                    $this->viewQuestion();
                    break;
                case 'markAsRead':
                    $this->markAsRead();
                    break;
                case 'respondToQuestion':
                    $this->respondToQuestion();
                    break;
                default:
                    // Redirect to dashboard if action is not recognized
                    header('Location: ../views/lecturer/lecturer_home.php');
                    exit();
            }
        } else {
            // Default action is to view forwarded questions
            $this->viewForwardedQuestions();
        }
    }
    
    /**
     * View all forwarded questions for the logged-in lecturer
     */
    private function viewForwardedQuestions() {
        // Check if user is logged in as lecturer
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        $lecturerId = $_SESSION['user_id'];
        $forwardedQuestions = $this->model->getForwardedQuestionsForLecturer($lecturerId);
        
        // Store questions in session for view to access
        $_SESSION['forwarded_questions'] = $forwardedQuestions;
        
        // Redirect to the view
        header('Location: ../views/lecturer/forwarded_questions.php');
        exit();
    }
    
    /**
     * View details of a specific forwarded question
     */
    private function viewQuestion() {
        // Check if user is logged in as lecturer
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error_message'] = "Invalid question ID.";
            header('Location: ../views/lecturer/lecturer_home.php');
            exit();
        }
        
        $forwardedId = $_GET['id'];
        $lecturerId = $_SESSION['user_id'];
        $question = $this->model->getForwardedQuestionById($forwardedId, $lecturerId);
        
        if (!$question) {
            $_SESSION['error_message'] = "Question not found.";
            header('Location: ../views/lecturer/lecturer_home.php');
            exit();
        }
        
        // Mark as read if it's unread
        if ($question['status'] === 'Unread') {
            $this->model->markAsRead($forwardedId, $lecturerId);
            $question['status'] = 'Read';
        }
        
        // Store question in session for view to access
        $_SESSION['question_details'] = $question;
        
        // Redirect to question detail view
        header('Location: ../views/lecturer/question_detail.php');
        exit();
    }
    
    /**
     * Mark a forwarded question as read via AJAX
     */
    private function markAsRead() {
        // Check if user is logged in as lecturer
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            exit();
        }
        
        if (!isset($_POST['forwarded_id']) || !is_numeric($_POST['forwarded_id'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid question ID']);
            exit();
        }
        
        $forwardedId = $_POST['forwarded_id'];
        $lecturerId = $_SESSION['user_id'];
        
        $result = $this->model->markAsRead($forwardedId, $lecturerId);
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        exit();
    }
    
    /**
     * Respond to a forwarded question
     */
    private function respondToQuestion() {
        // Check if user is logged in as lecturer
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        // Check if all required parameters are set
        if (!isset($_POST['forwarded_id']) || !isset($_POST['question_id']) || !isset($_POST['reply_text'])) {
            $_SESSION['error_message'] = "Missing required information.";
            header('Location: ../views/lecturer/forwardedQuestion.php');
            exit();
        }
        
        $forwardedId = $_POST['forwarded_id'];
        $questionId = $_POST['question_id'];
        $replyText = $_POST['reply_text'];
        $lecturerId = $_SESSION['user_id'];
        
        // Add the reply using the RepliedQuestionsModel
        $result = $this->repliedModel->addReply($questionId, $forwardedId, $lecturerId, $replyText);
        
        if ($result) {
            $_SESSION['success_message'] = "Your response has been sent successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to send response. Please try again.";
        }
        
        // Redirect back to forwarded questions
        header('Location: ../controller/ForwardedQuestionController.php?action=viewForwardedQuestions');
        exit();
    }
}

// Create controller instance and handle request
$controller = new ForwardedQuestionController();
$controller->handleRequest();
?>