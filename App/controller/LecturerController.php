<?php
require_once '../models/LecturerModel.php';

class LecturerController {
    private $model;
    
    public function __construct() {
        $this->model = new LecturerModel();
    }
    
    public function handleRequest() {
        // Check if action parameter exists
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            
            // Handle different actions
            switch ($action) {
                case 'list':
                    $this->list();
                    break;
                case 'viewProfile':
                    $this->viewLecturerProfile();
                    break;
                case 'add':
                    $this->add();
                    break;
                case 'edit':
                    $this->edit();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'myProfile':
                    $this->myProfile();
                    break;
                default:
                    echo 'Invalid action';
                    break;
            }
        } else {
            // Default action is to list lecturers
            $this->list();
        }
    }
    
    // List all lecturers
    public function list() {
        session_start();
        
        // Check if user is logged in with appropriate role
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            header('Location: ../views/login.php?error=unauthorized');
            exit();
        }
        
        // Only HOUS, admins, and students can view lecturer list
        $allowedRoles = ['hous', 'admin', 'student'];
        if (!in_array($_SESSION['role'], $allowedRoles)) {
            header('Location: ../views/home.php?error=unauthorized');
            exit();
        }
        
        // Get filter by category if provided
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        
        // Get lecturers
        if ($category) {
            $lecturers = $this->model->getLecturersByCategory($category);
        } else {
            $lecturers = $this->model->getAllLecturers();
        }
        
        // Get all categories for filter
        $categories = $this->model->getAllCategories();
        
        // Store data in session for view
        $_SESSION['lecturers'] = $lecturers;
        $_SESSION['categories'] = $categories;
        $_SESSION['selected_category'] = $category;
        
        // Load view based on user role
        if ($_SESSION['role'] === 'hous') {
            include '../views/houg/lecturer_list.php';
        } else {
            include '../views/lecturer_list.php';
        }
    }
    
    // View lecturer profile for HOUS
    public function viewLecturerProfile() {
        session_start();
        
        // Check if user is logged in as HOUS
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        // Check if lecturer ID is provided
        if (!isset($_GET['id'])) {
            $_SESSION['error_message'] = "Missing lecturer ID.";
            header('Location: ../controller/LecturerController.php?action=list');
            exit();
        }
        
        $lecturerId = (int)$_GET['id'];
        
        // Get lecturer details
        $lecturer = $this->model->getLecturerById($lecturerId);
        
        if (!$lecturer) {
            $_SESSION['error_message'] = "Lecturer not found.";
            header('Location: ../controller/LecturerController.php?action=list');
            exit();
        }
        
        // Get additional data for the lecturer
        $forwardedQuestions = $this->model->getLecturerForwardedQuestions($lecturer['user_id']);
        $replies = $this->model->getLecturerReplies($lecturer['user_id']);
        $stats = $this->model->getLecturerStats($lecturer['user_id']);
        
        // Store data in session for view to access
        $_SESSION['lecturer_profile'] = [
            'details' => $lecturer,
            'forwarded_questions' => $forwardedQuestions,
            'replies' => $replies,
            'stats' => $stats
        ];
        
        // Redirect to lecturer profile view
        header('Location: ../views/houg/lecturer_profile.php');
        exit();
    }
    
    // Add new lecturer (Admin/HOUS only)
    public function add() {
        session_start();
        
        // Check if user is admin or HOUS
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || 
            ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'hous')) {
            header('Location: ../views/login.php?error=unauthorized');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form submission
            $userData = [
                'user_id' => $_POST['user_id'],
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'department' => $_POST['department'],
                'category' => $_POST['category'],
                'profile_image' => $_POST['profile_image'] ?? null,
                'bio' => $_POST['bio'] ?? null
            ];
            
            $result = $this->model->addLecturer($userData);
            
            if ($result) {
                header('Location: ../controller/LecturerController.php?action=list&success=added');
            } else {
                header('Location: ../controller/LecturerController.php?action=add&error=failed');
            }
            exit();
        }
        
        // Display add form
        include '../views/houg/add_lecturer.php';
    }
    
    // Edit lecturer (Admin/HOUS only)
    public function edit() {
        session_start();
        
        // Check if user is admin or HOUS
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || 
            ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'hous')) {
            header('Location: ../views/login.php?error=unauthorized');
            exit();
        }
        
        // Get lecturer ID
        $lecturerId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        if (!$lecturerId) {
            header('Location: ../controller/LecturerController.php?action=list&error=invalid_id');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process form submission
            $userData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'department' => $_POST['department'],
                'category' => $_POST['category'],
                'profile_image' => $_POST['profile_image'] ?? null,
                'bio' => $_POST['bio'] ?? null
            ];
            
            $result = $this->model->updateLecturer($lecturerId, $userData);
            
            if ($result) {
                header('Location: ../controller/LecturerController.php?action=list&success=updated');
            } else {
                header("Location: ../controller/LecturerController.php?action=edit&id=$lecturerId&error=failed");
            }
            exit();
        }
        
        // Get lecturer details for form
        $lecturer = $this->model->getLecturerById($lecturerId);
        
        if (!$lecturer) {
            header('Location: ../controller/LecturerController.php?action=list&error=not_found');
            exit();
        }
        
        // Store in session for form
        $_SESSION['lecturer_to_edit'] = $lecturer;
        
        // Display edit form
        include '../views/houg/edit_lecturer.php';
    }
    
    // Delete lecturer (Admin/HOUS only)
    public function delete() {
        session_start();
        
        // Check if user is admin or HOUS
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || 
            ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'hous')) {
            header('Location: ../views/login.php?error=unauthorized');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lecturerId = isset($_POST['lecturer_id']) ? (int)$_POST['lecturer_id'] : null;
            
            if (!$lecturerId) {
                header('Location: ../controller/LecturerController.php?action=list&error=invalid_id');
                exit();
            }
            
            $result = $this->model->deleteLecturer($lecturerId);
            
            if ($result) {
                header('Location: ../controller/LecturerController.php?action=list&success=deleted');
            } else {
                header('Location: ../controller/LecturerController.php?action=list&error=delete_failed');
            }
            exit();
        }
        
        // If not POST request, redirect to list
        header('Location: ../controller/LecturerController.php?action=list');
        exit();
    }
    
    public function myProfile() {
        session_start();
        
        // Check if user is logged in as lecturer
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'lecturer') {
            header('Location: ../views/login.php?error=unauthorized');
            exit();
        }
        
        $lecturerId = $_SESSION['user_id'];
        $successMessage = '';
        $errorMessage = '';
        
        // Handle profile update
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
            // Sanitize inputs
            $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
            $department = filter_var(trim($_POST['department']), FILTER_SANITIZE_STRING);
            $category = filter_var(trim($_POST['category']), FILTER_SANITIZE_STRING);
            $bio = filter_var(trim($_POST['bio']), FILTER_SANITIZE_STRING);
            
            // Check if email is valid
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = "Please enter a valid email address.";
            } else {
                // Update users table
                $userResult = $this->model->updateUserInfo($lecturerId, $email, $phone);
                
                // Update lecturer info in lecturers table
                $lecturerResult = $this->model->updateLecturerByUserId($lecturerId, [
                    'name' => $name,
                    'email' => $email,
                    'department' => $department,
                    'category' => $category,
                    'bio' => $bio
                ]);
                
                if ($userResult && $lecturerResult) {
                    $successMessage = "Profile updated successfully!";
                } else {
                    $errorMessage = "Failed to update profile. Please try again.";
                }
            }
        }
        
        // Handle password change
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            // Check if current password is correct
            if (!$this->model->verifyPassword($lecturerId, $currentPassword)) {
                $errorMessage = "Current password is incorrect.";
            } 
            // Check if new passwords match
            else if ($newPassword !== $confirmPassword) {
                $errorMessage = "New passwords don't match.";
            } 
            // Check password length
            else if (strlen($newPassword) < 6) {
                $errorMessage = "New password must be at least 6 characters long.";
            } 
            else {
                // Update password
                $result = $this->model->updatePassword($lecturerId, $newPassword);
                
                if ($result) {
                    $successMessage = "Password changed successfully!";
                } else {
                    $errorMessage = "Failed to change password. Please try again.";
                }
            }
        }
        
        // Get user info
        $userInfo = $this->model->getUserById($lecturerId);
        
        // Get lecturer info
        $lecturerInfo = $this->model->getLecturerByUserId($lecturerId);
        
        // Merge user and lecturer info
        $profile = array_merge($userInfo ?? [], $lecturerInfo ?? []);
        
        // Store messages in session
        $_SESSION['success_message'] = $successMessage;
        $_SESSION['error_message'] = $errorMessage;
        $_SESSION['lecturer_profile'] = $profile;
        
        // Load view
        include '../views/lecturer/lecturer_profile.php';
    }
}

// Handle actions
if (isset($_GET['action'])) {
    $controller = new LecturerController();
    $controller->handleRequest();
} else {
    // Default action
    $controller = new LecturerController();
    $controller->list();
}
?>