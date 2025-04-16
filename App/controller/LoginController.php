<?php
session_start();
require_once '../models/User.php';

$userModel = new User();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if both fields are filled in
    if (!empty($_POST['username']) && !empty($_POST['loginPassword'])) {
        // Sanitize user inputs
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
        $password = trim($_POST['loginPassword']);  // No sanitization needed for password

        // Validate login (using plain text password for testing)
        $user = $userModel->validateLogin($username, $password);

        if ($user) {
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            // Store user data in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            switch ($user['role']) {
                case 'student':
                    header('Location: ../views/home.php');
                    break;
                case 'admin':
                    header('Location: ../views/admin_home.php');
                    break;
                case 'super_admin':
                    header('Location: ../views/admin/superadmin_home.html');
                    break;
                case 'hous':
                    require_once '../models/AcademicQuestionModel.php';
                    $academicQuestionModel = new AcademicQuestionModel();
                    // Fetch pending questions
                    $pendingQuestions = $academicQuestionModel->getPendingQuestions();
                    // Store data in session or pass to the view
                    $_SESSION['pending_questions'] = $pendingQuestions;
                    header('Location: ../views/houg/houg_home.php');
                    break;
                case 'lecturer':
                    header('Location: ../views/lecturer/lecturer_home.html');
                    break;
                default:
                    header('Location: ../../../../index.php');
                    break;
            }
            exit();
        } else {
            // Invalid credentials, redirect to login page with error message
            $_SESSION['login_error'] = 'Invalid login credentials.';
            header('Location: ../views/login.php');
            exit();
        }
    } else {
        // Handle missing fields
        $_SESSION['login_error'] = 'Both username and password are required!';
        header('Location: ../views/login.php');
        exit();
    }
} else {
    // Handle incorrect request method (GET instead of POST)
    $_SESSION['login_error'] = 'Invalid request method.';
    header('Location: ../views/login.php');
    exit();
}
