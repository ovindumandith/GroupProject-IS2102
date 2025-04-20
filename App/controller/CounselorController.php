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
public function viewLoggedInCounselorProfile() {
    // Check if the counselor is logged in
    if (!isset($_SESSION['counselor']['id'])) {
        header('Location: /GroupProject-IS2102/App/views/counselor_login.php');
        exit();
    }

    // Get counselor ID from session
    $counselorId = $_SESSION['counselor']['id'];

    // Fetch the logged-in counselor's profile
    $counselorProfile = $this->counselorModel->getLoggedInCounselorProfile($counselorId);

    // Check if profile was found
    if ($counselorProfile) {
        ob_start();
        require_once '../views/counselling/counselor_dashboard_profile.php'; // Pass data to the profile view
        $content = ob_get_clean();
        echo $content;
    } else {
        echo "Profile not found.";
    }
}

    
}

// Handle the incoming request based on the 'action' and 'id' parameters
$controller = new CounselingController();
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($action === 'viewCounselor' && $id > 0) {
    $controller->viewCounselor($id); // Show counselor's profile with reviews
} elseif ($action === 'viewLoggedInCounselorProfile') {
    $controller->viewLoggedInCounselorProfile(); // Show logged-in counselor's profile
} else {
    $controller->listCounselors(); // List all counselors
}
?>
