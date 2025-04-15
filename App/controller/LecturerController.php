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


    /**
 * Manage lecturer's own profile
 */
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
            $userResult = $this->updateUser($lecturerId, $email, $phone);
            
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
        if (!$this->verifyPassword($lecturerId, $currentPassword)) {
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
            $result = $this->updatePassword($lecturerId, $newPassword);
            
            if ($result) {
                $successMessage = "Password changed successfully!";
            } else {
                $errorMessage = "Failed to change password. Please try again.";
            }
        }
    }
    
    // Get user info
    $userInfo = $this->getUserInfo($lecturerId);
    
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

/**
 * Update user information
 * @param int $userId
 * @param string $email
 * @param string $phone
 * @return bool
 */
private function updateUser($userId, $email, $phone) {
    try {
        $db = new Database();
        $db = $db->connect();
        
        $query = "UPDATE users SET email = :email, phone = :phone WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Get user information
 * @param int $userId
 * @return array|false
 */
private function getUserInfo($userId) {
    try {
        $db = new Database();
        $db = $db->connect();
        
        $query = "SELECT * FROM users WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Verify user password
 * @param int $userId
 * @param string $password
 * @return bool
 */
private function verifyPassword($userId, $password) {
    try {
        $db = new Database();
        $db = $db->connect();
        
        $query = "SELECT password FROM users WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $storedPassword = $stmt->fetchColumn();
        
        // Verify the password - update this to match your existing password verification method
        return ($storedPassword === $password);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}

/**
 * Update user password
 * @param int $userId
 * @param string $newPassword
 * @return bool
 */
private function updatePassword($userId, $newPassword) {
    try {
        $db = new Database();
        $db = $db->connect();
        
        $query = "UPDATE users SET password = :password WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
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
        case 'myProfile':
            $controller->myProfile();
            break;  
        default:
            echo 'Invalid action';
            break;
    }
}
?>