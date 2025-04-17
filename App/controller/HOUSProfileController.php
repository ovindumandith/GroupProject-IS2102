
<?php
session_start();
require_once '../models/HOUSProfileModel.php';

class HOUSProfileController {
    private $model;
    
    public function __construct() {
        $this->model = new HOUSProfileModel();
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
                    header('Location: ../views/houg/houg_home.php');
                    exit();
            }
        } else {
            // Default action is to view profile
            $this->viewProfile();
        }
    }
    
    /**
     * View HOUS profile
     */
    private function viewProfile() {
        // Check if user is logged in as HOUS
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        $userId = $_SESSION['user_id'];
        $profileData = $this->model->getHOUSProfileById($userId);
        
        if (!$profileData) {
            $_SESSION['error_message'] = "Failed to retrieve profile information.";
            header('Location: ../views/houg/houg_home.php');
            exit();
        }
        
        // Store profile data in session for view to access
        $_SESSION['hous_profile'] = $profileData;
        
        // Redirect to profile view
        header('Location: ../views/houg/houg_profile.php');
        exit();
    }
    
    /**
     * Update HOUS profile information
     */
    private function updateProfile() {
        // Check if user is logged in as HOUS
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        // Check if all required parameters are set
        if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['phone'])) {
            $_SESSION['error_message'] = "Missing required information.";
            header('Location: ../controller/HOUSProfileController.php?action=viewProfile');
            exit();
        }
        
        $userId = $_SESSION['user_id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        
        // Basic validation
        if (empty($username) || empty($email)) {
            $_SESSION['error_message'] = "Username and email are required.";
            header('Location: ../controller/HOUSProfileController.php?action=viewProfile');
            exit();
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Invalid email format.";
            header('Location: ../controller/HOUSProfileController.php?action=viewProfile');
            exit();
        }
        
        // Update profile
        $result = $this->model->updateHOUSProfile($userId, $username, $email, $phone);
        
        if ($result) {
            $_SESSION['success_message'] = "Profile updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update profile. Please try again.";
        }
        
        // Redirect back to profile page
        header('Location: ../controller/HOUSProfileController.php?action=viewProfile');
        exit();
    }
    
    /**
     * Update HOUS password
     */
    private function updatePassword() {
        // Check if user is logged in as HOUS
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'hous') {
            header('Location: ../login.php?error=unauthorized');
            exit();
        }
        
        // Check if all required parameters are set
        if (!isset($_POST['current_password']) || !isset($_POST['new_password']) || !isset($_POST['confirm_password'])) {
            $_SESSION['error_message'] = "Missing required information.";
            header('Location: ../controller/HOUSProfileController.php?action=viewProfile');
            exit();
        }
        
        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // Verify current password (mock implementation - you should check against the hashed password in DB)
        $profileData = $this->model->getHOUSProfileById($userId);
        
        // In a real application, you should check the hashed password with password_verify()
        // For this simplified version, we'll just compare the raw passwords
        if (!$profileData || $currentPassword != $profileData['password']) {
            $_SESSION['error_message'] = "Current password is incorrect.";
            header('Location: ../controller/HOUSProfileController.php?action=viewProfile');
            exit();
        }
        
        // Check if new passwords match
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error_message'] = "New passwords do not match.";
            header('Location: ../controller/HOUSProfileController.php?action=viewProfile');
            exit();
        }
        
        // Basic password validation
        if (strlen($newPassword) < 6) {
            $_SESSION['error_message'] = "Password must be at least 6 characters.";
            header('Location: ../controller/HOUSProfileController.php?action=viewProfile');
            exit();
        }
        
        // Update password
        $result = $this->model->updateHOUSPassword($userId, $newPassword);
        
        if ($result) {
            $_SESSION['success_message'] = "Password updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update password. Please try again.";
        }
        
        // Redirect back to profile page
        header('Location: ../controller/HOUSProfileController.php?action=viewProfile');
        exit();
    }
}

// Create controller instance and handle request
$controller = new HOUSProfileController();
$controller->handleRequest();
?>