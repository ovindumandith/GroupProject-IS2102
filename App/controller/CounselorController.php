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
        require_once '../views/counselling/counsellor_index.php'; // Counselor list view
        $content = ob_get_clean();
        echo $content;
    }

    // View a single counselor's profile by ID
    public function viewCounselor($id) {
        if ($id > 0) {
            $counselor = $this->counselorModel->getCounselorById($id); // Fetch counselor data
            $reviews = $this->counselorModel->getReviewsByCounselorId($id); // Fetch reviews
            
            if ($counselor) {
                ob_start();
                include_once '../../App/views/counselling/counsellor_profile.php'; // Pass data to the view
                $content = ob_get_clean();
                echo $content;
            } else {
                echo "Counselor not found.";
            }
        } else {
            echo "Invalid counselor ID.";
        }
    }
}

// Handle the incoming request based on the 'action' and 'id' parameters
$controller = new CounselingController();
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($action === 'viewCounselor' && $id > 0) {
    $controller->viewCounselor($id); // Show counselor's profile with reviews
} else {
    $controller->listCounselors(); // List all counselors
}
?>
