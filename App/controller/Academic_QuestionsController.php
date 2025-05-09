<?php
require_once '../models/AcademicQuestionModel.php';

class Academic_QuestionsController {
    private $model;

    public function __construct() {
        $this->model = new AcademicQuestionModel();
    }

    // Method to submit a new academic question
    public function submitQuestion() {
        session_start();
        $userId = $_SESSION['user_id']; // Retrieve user ID from session (logged-in student)
        $indexNo = $_POST['index_no'];
        $regNo = $_POST['reg_no'];
        $fullName = $_POST['full_name'];
        $faculty = $_POST['faculty'];
        $category = $_POST['category'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $question = $_POST['question'];

        if ($this->model->submitQuestion($userId, $indexNo, $regNo, $fullName, $faculty, $category, $telephone, $email, $question)) {
            header('Location: ../views/Academic_Help.php?success=1'); // Redirect on success
            exit();
        } else {
            header(''); // Redirect on error
            exit();
        }
    }

    // Method to view all academic questions (for admin or authorized users)
    public function viewAllQuestions() {
        session_start();
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: login.php'); // Redirect if not logged in
            exit();
        }

        $questions = $this->model->getAllQuestions(); // Fetch all questions from the model

        include '../views/admin/admin_academicHelp/admin_academicHelpView.php'; // Pass the questions data to the view
    }

        public function viewAllQuestions_hous() {
        session_start();
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
            header('Location: login.php'); // Redirect if not logged in
            exit();
        }
        $questions = $this->model->getAllQuestions(); // Fetch all questions from the model
        include '../views/houg/view_academicQuestions.php'; // Pass the questions data to the view
    }

    // Method to view questions submitted by a specific user
    public function viewUserQuestions() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php'); // Redirect if not logged in
            exit();
        }

        $userId = $_SESSION['user_id'];
        $questions = $this->model->getUserQuestions($userId); // Fetch questions submitted by the logged-in user

        include '../views/showStudentAcademicRequest.php'; // Pass data to the view
    }
    

    // Method to update an academic question
    public function updateQuestion() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
            $questionId = $_POST['question_id'];
            $indexNo = $_POST['index_no'];
            $regNo = $_POST['reg_no'];
            $fullName = $_POST['full_name'];
            $faculty = $_POST['faculty'];
            $category = $_POST['category'];
            $telephone = $_POST['telephone'];
            $email = $_POST['email'];
            $question = $_POST['question'];

            if ($this->model->updateQuestion($questionId, $indexNo, $regNo, $fullName, $faculty, $category, $telephone, $email, $question)) {
                $_SESSION['success'] = 'Your question has been updated successfully.';
                header('Location: AcademicQuestionController.php?action=viewUserQuestions');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to update the question.';
                header('Location: AcademicQuestionController.php?action=viewUserQuestions');
                exit();
            }
        }
    }

    // Method to delete an academic question
    public function deleteQuestion() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
            $questionId = $_POST['question_id'];

            if ($this->model->deleteQuestion($questionId)) {
                $_SESSION['success'] = 'Your question has been deleted successfully.';
                header('Location: Academic_QuestionsController.php?action=viewUserQuestions');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to delete the question.';
                header('Location: Academic_QuestionsController.php?action=viewUserQuestions');
                exit();
            }
        }
    }
        public function deleteQuestion_admin() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
            $questionId = $_POST['question_id'];

            if ($this->model->deleteQuestion($questionId)) {
                $_SESSION['success'] = 'Your question has been deleted successfully.';
                header('Location: Academic_QuestionsController.php?action=viewAllQuestions');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to delete the question.';
                header('Location: Academic_QuestionsController.php?action=viewAllQuestions');
                exit();
            }
        }
    }

        public function deleteQuestion_hous() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
            $questionId = $_POST['question_id'];

            if ($this->model->deleteQuestion($questionId)) {
                $_SESSION['success'] = 'Your question has been deleted successfully.';
                header('Location: Academic_QuestionsController.php?action=viewAllQuestions_hous');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to delete the question.';
                header('Location: Academic_QuestionsController.php?action=viewAllQuestions_hous');
                exit();
            }
        }
    }

    // Method to update the status of an academic question
    public function updateQuestionStatus() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'], $_POST['status'])) {
            $questionId = $_POST['question_id'];
            $status = $_POST['status'];

            if ($this->model->updateQuestionStatus($questionId, $status)) {
                $_SESSION['status_update_success'] = 'Question status updated successfully.';
            } else {
                $_SESSION['status_update_error'] = 'Failed to update question status.';
            }

            header('Location: AcademicQuestionController.php?action=viewAllQuestions');
            exit();
        }
    }
    // Method to fetch a specific question and its responses
    public function getQuestion() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
            $questionId = $_POST['question_id'];

            // Fetch question details and responses from the model
            $data = $this->model->getQuestionWithResponses($questionId);

            if ($data) {
                include '../views/viewQuestion.php'; // Redirect to the view page with question details
            } else {
                $_SESSION['error'] = 'Error fetching question details.';
                header('Location: AcademicQuestionController.php?action=viewAllQuestions');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Invalid request.';
            header('Location: AcademicQuestionController.php?action=viewAllQuestions');
            exit();
        }
    }
    // Method to mark a question as resolved by Student
    public function markAsResolved() {
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
        $questionId = $_POST['question_id'];

        // Update the question status to 'Resolved' using the model
        if ($this->model->updateQuestionStatusByStudent($questionId, 'Resolved')) {
            $_SESSION['success'] = 'The question has been marked as resolved.';
            header('Location: Academic_QuestionsController.php?action=viewUserQuestions');
            exit();
        } else {
            $_SESSION['error'] = 'Failed to mark the question as resolved.';
            header('Location: Academic_QuestionsController.php?action=viewUserQuestions');
            exit();
        }
    }
}
public function updateQuestionModalOpen() {
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'], $_POST['updated_question'])) {
        $questionId = $_POST['question_id'];
        $updatedQuestion = $_POST['updated_question'];

        if ($this->model->updateQuestionModal($questionId, $updatedQuestion)) {
            $_SESSION['success'] = 'Question updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update the question.';
        }

        // Redirect back to the academic questions page
        header('Location: Academic_QuestionsController.php?action=viewUserQuestions');
        exit();
    }
}
    public function replyQuestion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $questionId = $_POST['question_id'];
            $response = $_POST['reply_text'];

            // Get the logged-in admin's ID (assuming it's stored in the session)
            session_start();
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(["success" => false, "message" => "Admin not logged in."]);
                exit();
            }
            $adminId = $_SESSION['user_id'];

            // Save the reply
            if ($this->model->saveReply($questionId, $adminId, $response)) {
                // Return success response
                echo json_encode(["success" => true, "message" => "Reply added successfully!"]);
            } else {
                // Return error response
                echo json_encode(["success" => false, "message" => "Failed to add reply. Please try again."]);
            }
            header('Location: Academic_QuestionsController.php?action=viewAllQuestions_hous');
            exit();
        }
    }
        public function replyQuestion_hous() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $questionId = $_POST['question_id'];
            $response = $_POST['reply_text'];

            // Get the logged-in admin's ID (assuming it's stored in the session)
            session_start();
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(["success" => false, "message" => "Head of Undergraduate Studies not logged in."]);
                exit();
            }
            $adminId = $_SESSION['user_id'];

            // Save the reply
            if ($this->model->saveReply($questionId, $adminId, $response)) {
                // Set success message
                $_SESSION['message'] = "Reply added successfully!";
            } else {
                // Set error message
                $_SESSION['message'] = "Failed to add reply. Please try again.";
            }

            // Redirect back to the admin view
            header('Location: Academic_QuestionsController.php?action=viewAllQuestions_hous');
            exit();
        }
    }





    
}

// Check if an action is set in the query string
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller = new Academic_QuestionsController();

    switch ($action) {
        case 'submitQuestion':
            $controller->submitQuestion();
            break;
        case 'viewAllQuestions':
            $controller->viewAllQuestions();
            break;
        case 'viewUserQuestions':
            $controller->viewUserQuestions();
            break;
        case 'updateQuestion':
            $controller->updateQuestion();
            break;
        case 'deleteQuestion':
            $controller->deleteQuestion();
            break;
        case 'updateQuestionStatus':
            $controller->updateQuestionStatus();
            break;
        case 'getQuestion':
            $controller->getQuestion();
            break; 
        case 'markAsResolved':
            $controller->markAsResolved();
            break;   
        case 'updateQuestionModalOpen':
            $controller->updateQuestionModalOpen();
            break;  
        case 'deleteQuestion_admin':
            $controller->deleteQuestion_admin();
            break;   
        case 'replyQuestion':
            $controller->replyQuestion();
            break;  
        case 'viewAllQuestions_hous':
            $controller->viewAllQuestions_hous();
            break;  
        case 'deleteQuestion_hous': 
            $controller->deleteQuestion_hous();
            break;  
        case 'replyQuestion_hous':  
            $controller->replyQuestion_hous();
            break;                   
        default:
            echo 'Invalid action';
    }
}
?>
