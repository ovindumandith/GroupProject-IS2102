<?php
session_start();
require_once '../models/User.php'; // Adjust the path accordingly

$userModel = new User();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are filled
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['year']) && !empty($_POST['password'])) {
        
        // Sanitize the input data
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
        $year = filter_var(trim($_POST['year']), FILTER_SANITIZE_STRING);
        $password = trim($_POST['password']); // Plain text password

        // Register the user
        if ($userModel->registerUser($username, $email, $phone, $year, $password)) {
            // Set a success message in session
            $_SESSION['signup_success'] = 'Registration successful! You can now log in.';
            header('Location: ../views/login.php'); // Redirect to login page
            exit();
        } else {
            // Registration failed, set an error message
            $_SESSION['signup_error'] = 'Username or Email already exists.';
            header('Location: ../views/login.php'); // Redirect to signup page
            exit();
        }
    } else {
        // If any field is missing, set an error message
        $_SESSION['signup_error'] = 'All fields are required!';
        header('Location: ../views/login.php');
        exit();
    }
} else {
    // Handle incorrect request method
    $_SESSION['signup_error'] = 'Invalid request method.';
    header('Location: ../views/login.php');
    exit();
}
