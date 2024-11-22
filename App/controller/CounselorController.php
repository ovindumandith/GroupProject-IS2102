<?php
require_once '../models/CounselorModel.php';

class CounselingController {
    private $counselorModel;

    public function __construct() {
        $this->counselorModel = new CounselorModel();
    }

    // List all counselors
    public function listCounselors() {
        $counselors = $this->counselorModel->getAllCounselors();
        ob_start();
        require_once '../../App/views/counselling/counsellor_index.php'; // Display the list of counselors
        $content = ob_get_clean();
        echo $content;
    }

    // View a single counselor's profile by ID
    public function viewCounselor($id) {
        $counselor = $this->counselorModel->getCounselorById($id);

        if ($counselor) {
            ob_start();
            include_once '../../App/views/counselling/counsellor_profile.php'; // Show the profile of the selected counselor
            $content = ob_get_clean();
            echo $content;
        } else {
            echo "Counselor not found.";
        }
    }
}

// Handling the request based on the 'action' query parameter
$controller = new CounselingController();
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($action === 'view' && $id > 0) {
    $controller->viewCounselor($id); // View a specific counselor's profile
} else {
    $controller->listCounselors(); // List all counselors
}
?>
