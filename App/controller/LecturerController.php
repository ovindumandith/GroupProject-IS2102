<?php
require_once '../models/LecturerModel.php';

class LecturerController {
    private $model;
    
    public function __construct() {
        $this->model = new LecturerModel();
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
    
    // View lecturer profile
    public function viewProfile() {
        session_start();
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/login.php?error=unauthorized');
            exit();
        }
        
        // Get lecturer ID from request
        $lecturerId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        if (!$lecturerId) {
            header('Location: ../controller/LecturerController.php?action=list&error=invalid_id');
            exit();
        }
        
        // Get lecturer details
        $lecturer = $this->model->getLecturerById($lecturerId);
        
        if (!$lecturer) {
            header('Location: ../controller/LecturerController.php?action=list&error=not_found');
            exit();
        }
        
        // Store in session for view
        $_SESSION['lecturer_profile'] = $lecturer;
        
        // Load view
        include '../views/lecturer_profile.php';
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
}

// Handle actions
if (isset($_GET['action'])) {
    $controller = new LecturerController();
    $action = $_GET['action'];
    
    switch ($action) {
        case 'list':
            $controller->list();
            break;
        case 'viewProfile':
            $controller->viewProfile();
            break;
        case 'add':
            $controller->add();
            break;
        case 'edit':
            $controller->edit();
            break;
        case 'delete':
            $controller->delete();
            break;
        default:
            echo 'Invalid action';
            break;
    }
}
?>