<?php
require_once '../models/LecturerModel.php';

class LecturerController {
    private $lecturerModel;

    public function __construct() {
        $this->lecturerModel = new LecturerModel();
    }

    public function showLecturers() {
        session_start();
        
        // Redirect if not logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../views/auth/login.php'); 
            exit();
        }

        // Fetch lecturers
        $lecturers = $this->lecturerModel->getAllLecturers();

        // Ensure $lecturers is always an array
        if (!$lecturers) {
            $lecturers = []; 
        }

        // Load the view
        require_once '../views/houg/view_lecturers.php'; 
    }
}

// Instantiate the controller
$controller = new LecturerController();

// Check if an action is provided
$action = isset($_GET['action']) ? $_GET['action'] : 'showLecturers';

// Execute the requested action
switch ($action) {
    case 'showLecturers':
        $controller->showLecturers();
        break;
    default:
        echo '<p style="color: red;">Invalid action</p>';
        break;
}
