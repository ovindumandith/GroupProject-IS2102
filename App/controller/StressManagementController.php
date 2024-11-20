<?php
session_start(); // Start the session to access user data
require_once '../../../GroupProject-IS2102/App/models/StressManagementModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handling form submission (data insertion)
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $sleepHours = isset($_POST['sleep']) ? (int) $_POST['sleep'] : null;
    $exerciseHours = isset($_POST['exercise']) ? (int) $_POST['exercise'] : null;
    $workHours = isset($_POST['workload']) ? (int) $_POST['workload'] : null;
    $moodStatus = isset($_POST['mood']) ? (int) $_POST['mood'] : null;

    if (
        $userId &&
        $sleepHours >= 0 && $sleepHours <= 24 &&
        $exerciseHours >= 0 && $exerciseHours <= 24 &&
        $workHours >= 0 && $workHours <= 24 &&
        $moodStatus >= 1 && $moodStatus <= 10
    ) {
        $model = new StressManagementModel();
        $result = $model->saveStressData($userId, $sleepHours, $exerciseHours, $workHours, $moodStatus);

        if ($result) {
            header('Location: ../../../views/stress_management/stress_management_index.php');
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save data.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input. Please ensure all fields are correctly filled.']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'view_records') {
    // Handling record retrieval
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($userId) {
        $model = new StressManagementModel();
        $records = $model->getStressRecords($userId);

        if ($records) {
            $_SESSION['stress_records'] = $records;
            header('Location: ../../../views/stress_management/stress_management_index.php');
            exit();
        } else {
            $_SESSION['toast_message'] = 'No records found.';
            header('Location: ../../../views/stress_management/stress_management_index.php');
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not authenticated.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
