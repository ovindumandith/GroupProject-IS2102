<?php
require_once '../models/ForwardedQuestionModel.php';
require_once '../models/AcademicQuestionModel.php';

class ForwardedQuestionController {
    private $model;
    private $academicQuestionModel;
    
    public function __construct() {
        $this->model = new ForwardedQuestionModel();
        $this->academicQuestionModel = new AcademicQuestionModel();
    }
    
    // Forward a question to lecturers by category
    public function forwardQuestion() {
        session_start();
        
        // Check if user is logged in as HOUS
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            exit();
        }
        
        // Get parameters
        $questionId = isset($_POST['question_id']) ? (int)$_POST['question_id'] : 0;
        $housId = $_SESSION['user_id'];
        
        if (!$questionId) {
            echo json_encode(['success' => false, 'message' => 'Invalid question ID']);
            exit();
        }
        
        // Get question details to get the category
        $question = $this->academicQuestionModel->getQuestionById($questionId);
        if (!$question) {
            echo json_encode(['success' => false, 'message' => 'Question not found']);
            exit();
        }
        
        // Forward the question
        $result = $this->model->forwardQuestionToLecturers($questionId, $question['category'], $housId);
        
        // Update question status to "Forwarded"
        if ($result) {
            $this->academicQuestionModel->updateQuestionStatus($questionId, 'Forwarded');
            echo json_encode(['success' => true, 'message' => 'Question forwarded successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to forward question or no lecturers found for this category']);
        }
        exit();
    }
    
    // View forwarded questions for a lecturer
    public function viewForwardedQuestions() {
        session_start();
        
        // Check if user is logged in as lecturer
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
            header('Location: ../views/login.php?error=unauthorized');
            exit();
        }
        
        $lecturerId = $_SESSION['user_id'];
        $forwardedQuestions = $this->model->getForwardedQuestionsForLecturer($lecturerId);
        
        // Store in session for view
        $_SESSION['forwarded_questions'] = $forwardedQuestions;
        
        // Include view
        include '../views/lecturer/forwarded_questions.php';
    }
    
    // Mark a forwarded question as read
    public function markAsRead() {
        session_start();
        
        // Check if user is logged in as lecturer
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            exit();
        }
        
        $forwardedQuestionId = isset($_POST['forwarded_id']) ? (int)$_POST['forwarded_id'] : 0;
        $lecturerId = $_SESSION['user_id'];
        
        if (!$forwardedQuestionId) {
            echo json_encode(['success' => false, 'message' => 'Invalid forwarded question ID']);
            exit();
        }
        
        $result = $this->model->markAsRead($forwardedQuestionId, $lecturerId);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Marked as read']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to mark as read']);
        }
        exit();
    }
    
    // View a specific forwarded question
    public function viewQuestion() {
        session_start();
        
        // Check if user is logged in as lecturer
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
            header('Location: ../views/login.php?error=unauthorized');
            exit();
        }
        
        $forwardedId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $lecturerId = $_SESSION['user_id'];
        
        if (!$forwardedId) {
            header('Location: ../controller/ForwardedQuestionController.php?action=viewForwardedQuestions&error=invalid_id');
            exit();
        }
        
        // Get the forwarded question details
        $forwardedQuestion = $this->model->getForwardedQuestionById($forwardedId, $lecturerId);
        
        if (!$forwardedQuestion) {
            header('Location: ../controller/ForwardedQuestionController.php?action=viewForwardedQuestions&error=not_found');
            exit();
        }
        
        // Mark as read if currently unread
        if ($forwardedQuestion['status'] === 'Unread') {
            $this->model->markAsRead($forwardedId, $lecturerId);
        }
        
        // Get the academic question details
        $academicQuestion = $this->academicQuestionModel->getQuestionById($forwardedQuestion['question_id']);
        
        // Store in session for view
        $_SESSION['forwarded_question'] = $forwardedQuestion;
        $_SESSION['academic_question'] = $academicQuestion;
        
        // Redirect to detailed view
        include '../views/lecturer/forwarded_question_detail.php';
    }
    
    // Respond to a forwarded question
    public function respondToQuestion() {
        session_start();
        
        // Check if user is logged in as lecturer
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
            header('Location: ../views/login.php?error=unauthorized');
            exit();
        }
        
        $forwardedQuestionId = isset($_POST['forwarded_id']) ? (int)$_POST['forwarded_id'] : 0;
        $questionId = isset($_POST['question_id']) ? (int)$_POST['question_id'] : 0;
        $replyText = isset($_POST['reply_text']) ? trim($_POST['reply_text']) : '';
        $lecturerId = $_SESSION['user_id'];
        
        if (!$forwardedQuestionId || !$questionId || empty($replyText)) {
            $_SESSION['error_message'] = 'All fields are required';
            header('Location: ../controller/ForwardedQuestionController.php?action=viewForwardedQuestions');
            exit();
        }
        
        // Add reply to the question using saveReply method from AcademicQuestionModel
        $replyResult = $this->academicQuestionModel->saveReply($questionId, $lecturerId, $replyText);
        
        if ($replyResult) {
            // Mark as responded
            $this->model->markAsResponded($forwardedQuestionId, $lecturerId);
            
            // Update question status to "Resolved"
            $this->academicQuestionModel->updateQuestionStatus($questionId, 'Resolved');
            
            $_SESSION['success_message'] = 'Reply sent successfully';
        } else {
            $_SESSION['error_message'] = 'Failed to send reply';
        }
        
        header('Location: ../controller/ForwardedQuestionController.php?action=viewForwardedQuestions');
        exit();
    }
}

// Handle actions
if (isset($_GET['action'])) {
    $controller = new ForwardedQuestionController();
    $action = $_GET['action'];
    
    switch ($action) {
        case 'forwardQuestion':
            $controller->forwardQuestion();
            break;
        case 'viewForwardedQuestions':
            $controller->viewForwardedQuestions();
            break;
        case 'markAsRead':
            $controller->markAsRead();
            break;
        case 'respondToQuestion':
            $controller->respondToQuestion();
            break;
        case 'viewQuestion':
            $controller->viewQuestion();
            break;      
        default:
            echo 'Invalid action';
            break;
    }
}
?>