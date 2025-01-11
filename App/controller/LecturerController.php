<?php
require_once '../models/LecturerModel.php';

class LecturerController {

    private $lecturerModel;

    public function __construct() {
        $this->lecturerModel = new LecturerModel();
    }

    public function showLecturers() {
        // Fetch all lecturers from the model
        $lecturers = $this->lecturerModel->getAllLecturers();

        // Check if any lecturers were found
        if ($lecturers) {
            // Pass the lecturers data to the view
            include '../views/houg/view_lecturers.php';
        } else {
            // If no lecturers found, display a message
            echo "<p>No lecturers found.</p>";
        }
    }
    public function viewLecturer($id){
        
    }

    
}
$controller = new LecturerController();
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($action === 'viewCounselor' && $id > 0) {
    $controller->viewLecturer($id); // Show counselor's profile with reviews
}else {
    $controller->showLecturers(); // List all counselors
}
