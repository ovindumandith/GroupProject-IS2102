<?php
// File: controller/AdminProfileController.php
session_start();
require_once '../models/AdminProfileModel.php';

class AdminProfileController {
    private $model;
    
    public function __construct() {
        $this->model = new AdminProfileModel();
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
                case 'viewProfile':
                    $this->viewProfile();
                    break;
                case 'updateProfile':
                    $this->updateProfile();
                    break;
                case 'updatePassword':
                    $this->updatePassword();
                    break;
                default:
                    // Redirect to dashboard if action is not recognized
                    header('Location: ../views/admin_home.php');
                    exit();
            }
        } else {
            // Default action is to view profile
            $this->viewProfile();
        }
    }
    
    /**
     * View admin profile
     */
    private function viewProfile() {
        // Check if user is logged in as admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        $userId = $_SESSION['user_id'];
        $profileData = $this->model->getAdminProfileById($userId);
        
        if (!$profileData) {
            $_SESSION['error_message'] = "Failed to retrieve profile information.";
            header('Location: ../views/admin_home.php');
            exit();
        }
        
        // Get system statistics for admin dashboard
        $stats = $this->model->getSystemStats();
        
        // Store profile data in session for view to access
        $_SESSION['admin_profile'] = $profileData;
        $_SESSION['system_stats'] = $stats;
        
        // Redirect to profile view
        header('Location: ../views/admin/admin_profile.php');
        exit();
    }
    
    /**
     * Update admin profile information
     */
    private function updateProfile() {
        // Check if user is logged in as admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        // Check if all required parameters are set
        if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['phone'])) {
            $_SESSION['error_message'] = "Missing required information.";
            header('Location: ../controller/AdminProfileController.php?action=viewProfile');
            exit();
        }
        
        $userId = $_SESSION['user_id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        
        // Basic validation
        if (empty($username) || empty($email)) {
            $_SESSION['error_message'] = "Username and email are required.";
            header('Location: ../controller/AdminProfileController.php?action=viewProfile');
            exit();
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Invalid email format.";
            header('Location: ../controller/AdminProfileController.php?action=viewProfile');
            exit();
        }
        
        // Update profile
        $result = $this->model->updateAdminProfile($userId, $username, $email, $phone);
        
        if ($result) {
            $_SESSION['success_message'] = "Profile updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update profile. Please try again.";
        }
        
        // Redirect back to profile page
        header('Location: ../controller/AdminProfileController.php?action=viewProfile');
        exit();
    }
    
    /**
     * Update admin password
     */
    private function updatePassword() {
        // Check if user is logged in as admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        // Check if all required parameters are set
        if (!isset($_POST['current_password']) || !isset($_POST['new_password']) || !isset($_POST['confirm_password'])) {
            $_SESSION['error_message'] = "Missing required information.";
            header('Location: ../controller/AdminProfileController.php?action=viewProfile');
            exit();
        }
        
        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // Verify current password
        if (!$this->model->verifyPassword($userId, $currentPassword)) {
            $_SESSION['error_message'] = "Current password is incorrect.";
            header('Location: ../controller/AdminProfileController.php?action=viewProfile');
            exit();
        }
        
        // Check if new passwords match
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error_message'] = "New passwords do not match.";
            header('Location: ../controller/AdminProfileController.php?action=viewProfile');
            exit();
        }
        
        // Basic password validation
        if (strlen($newPassword) < 6) {
            $_SESSION['error_message'] = "Password must be at least 6 characters.";
            header('Location: ../controller/AdminProfileController.php?action=viewProfile');
            exit();
        }
        
        // Update password
        $result = $this->model->updateAdminPassword($userId, $newPassword);
        
        if ($result) {
            $_SESSION['success_message'] = "Password updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update password. Please try again.";
        }
        
        // Redirect back to profile page
        header('Location: ../controller/AdminProfileController.php?action=viewProfile');
        exit();
    }
}

// Create controller instance and handle request
$controller = new AdminProfileController();
$controller->handleRequest();
?>