<?php
/**
 * UserProfileController - Handles all user-related actions and routes
 */
require_once '../models/UserProfileModel.php';

class UserProfileController {
    private $UserProfileModel;
    
    /**
     * Constructor - Initialize the User Model
     */
    public function __construct() {
        $this->UserProfileModel = new UserProfileModel();
    }
    
    /**
     * Show user profile page
     */
    public function showProfile() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/login.php');
            exit();
        }
        
        // Get user data
        $user_id = $_SESSION['user_id'];
        $user = $this->UserProfileModel->getUserById($user_id);
        
        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header('Location: ../views/login.php');
            exit();
        }
        
        // Check for success message
        $update_success = isset($_SESSION['update_success']) ? $_SESSION['update_success'] : false;
        
        // Clear the session message after reading it
        if (isset($_SESSION['update_success'])) {
            unset($_SESSION['update_success']);
        }
        
        // Load view
        include_once '../views/profile.php';
    }
    
    /**
     * Update user profile
     */
    public function updateProfile() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/login.php');
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        
        // Validate form data
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: UserProfileController.php?action=showProfile');
            exit();
        }
        
        // Collect and sanitize input
        $username = trim($_POST['username']);
        $password = trim($_POST['password']); // Store as plain text as requested
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $year = trim($_POST['year']);
        
        // Basic validation
        $errors = [];
        
        if (empty($username)) {
            $errors[] = "Username is required.";
        }
        
        if (empty($password)) {
            $errors[] = "Password is required.";
        }
        
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }
        
        if (empty($phone)) {
            $errors[] = "Phone number is required.";
        } elseif (!preg_match("/^[0-9]{10}$/", preg_replace("/[^0-9]/", "", $phone))) {
            $errors[] = "Please enter a valid phone number (10 digits).";
        }
        
        if (empty($year)) {
            $errors[] = "Year is required.";
        }
        
        // Check if username already exists for another user
        if ($this->UserProfileModel->usernameExists($username, $user_id)) {
            $errors[] = "Username already taken by another user.";
        }
        
        // Check if email already exists for another user
        if ($this->UserProfileModel->emailExists($email, $user_id)) {
            $errors[] = "Email already in use by another account.";
        }
        
        // If there are errors, redirect back with errors
        if (!empty($errors)) {
            $_SESSION['update_errors'] = $errors;
            header('Location: UserProfileController.php?action=showProfile');
            exit();
        }
        
        // Prepare data for update
        $userData = [
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'phone' => $phone,
            'year' => $year
        ];
        
        // Update user profile
        if ($this->UserProfileModel->updateUser($user_id, $userData)) {
            $_SESSION['update_success'] = true;
            
            // Update session variables if needed
            $_SESSION['username'] = $username;
        } else {
            $_SESSION['update_errors'] = ["Failed to update profile. Please try again."];
        }
        
        // Redirect back to profile
        header('Location: UserProfileController.php?action=showProfile');
        exit();
    }
    
    /**
     * View user appointments
     */
    public function viewAppointments() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/login.php');
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        
        // Get user data for header
        $user = $this->UserProfileModel->getUserById($user_id);
        
        // Get appointments
        $appointments = $this->UserProfileModel->getUserAppointments($user_id);
        
        // Load view
        include_once '../views/user/appointments.php';
    }
    
    /**
     * View user academic questions/requests
     */
    public function viewAcademicRequests() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/login.php');
            exit();
        }
        
        $user_id = $_SESSION['user_id'];
        
        // Get user data for header
        $user = $this->UserProfileModel->getUserById($user_id);
        
        // Get academic questions
        $academicQuestions = $this->UserProfileModel->getUserAcademicQuestions($user_id);
        
        // Load view
        include_once '../views/user/academic_requests.php';
    }
    
    /**
     * Handle any other actions or requests
     */
    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'showProfile';
        
        switch ($action) {
            case 'updateProfile':
                $this->updateProfile();
                break;
            case 'viewAppointments':
                $this->viewAppointments();
                break;
            case 'viewAcademicRequests':
                $this->viewAcademicRequests();
                break;
            case 'showProfile':
            default:
                $this->showProfile();
                break;
        }
    }
}

// Process the request
$controller = new UserProfileController();
$controller->handleRequest();