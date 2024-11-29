<?php
session_start();
require_once '../models/CounselorLoginRegisterModel.php';  // Adjust the path if needed

$counselorModel = new CounselorLoginRegisterModel();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure required fields are filled
    if (
        !empty($_POST['name']) &&
        !empty($_POST['type']) &&
        !empty($_POST['email']) &&
        !empty($_POST['password']) &&
        !empty($_POST['username'])
    ) {
        // Sanitize input data
        $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        $type = filter_var(trim($_POST['type']), FILTER_SANITIZE_STRING);
        $specialization = isset($_POST['specialization']) ? filter_var(trim($_POST['specialization']), FILTER_SANITIZE_STRING) : null;
        $profileImage = isset($_POST['profile_image']) ? filter_var(trim($_POST['profile_image']), FILTER_SANITIZE_STRING) : null;
        $description = isset($_POST['description']) ? filter_var(trim($_POST['description']), FILTER_SANITIZE_STRING) : null;
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);  // Note: Plaintext password (should hash in production)
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);

        // Check if the username or email already exists
        if ($counselorModel->isCounselorExist($username, $email)) {
            $_SESSION['signup_error'] = 'Username or email already exists. Please use a different one!';
            header('Location: ../views/counselor_register.php');  // Redirect to registration form
            exit();
        }

        // Register the counselor
        if ($counselorModel->registerCounselor($name, $type, $specialization, $profileImage, $description, $email, $password, $username)) {
            $_SESSION['signup_success'] = 'Registration successful! You can now log in.';
            header('Location: ../views/counselor_login.php');  // Redirect to login form
            exit();
        } else {
            $_SESSION['signup_error'] = 'There was an error during registration. Please try again.';
            header('Location: ../views/counselor_register.php');  // Redirect to registration form
            exit();
        }
    } else {
        $_SESSION['signup_error'] = 'All required fields must be filled!';
        header('Location: ../views/counselor_register.php');  // Redirect to registration form
        exit();
    }
} else {
    $_SESSION['signup_error'] = 'Invalid request method.';
    header('Location: ../views/counselor_register.php');  // Redirect to registration form
    exit();
}
?>
