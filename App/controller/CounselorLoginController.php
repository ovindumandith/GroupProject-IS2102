<?php
session_start();
require_once '../models/CounselorLoginRegisterModel.php';

$counselorModel = new CounselorLoginRegisterModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['loginusername']) && !empty($_POST['loginPassword'])) {
        // Sanitize input data
        $username = filter_var(trim($_POST['loginusername']), FILTER_SANITIZE_STRING);
        $password = trim($_POST['loginPassword']);

        // Validate login
        $counselor = $counselorModel->validateLogin($username, $password);
        if ($counselor) {
            // Successful login
            $_SESSION['counselor'] = [
                'id' => $counselor['id'], 
                'name' => $counselor['name'],
                'type' => $counselor['type'],
                'specialization' => $counselor['specialization'],
                'email' => $counselor['email'],
                'username' => $counselor['username']
            ];
            header('Location: ../views/counselling/counselor_dashboard.php'); // Redirect to dashboard
            exit();
        } else {
            // Invalid login credentials
            $_SESSION['login_error'] = 'Invalid username or password!';
            header('Location: ../views/counselling/counselor_login.php');
            exit();
        }
    } else {
        $_SESSION['login_error'] = 'Both username and password are required!';
        header('Location: ../views/counselling/counselor_login.php');
        exit();
    }
} else {
    $_SESSION['login_error'] = 'Invalid request method.';
    header('Location: ../views/counselling/counselor_login.php');
    exit();
}
?>
