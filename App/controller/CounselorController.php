<?php
require_once '../models/CounselorModel.php';

class CounselorController {
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

    // View logged-in counselor's profile
    public function viewLoggedInCounselorProfile() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if the counselor is logged in
        if (!isset($_SESSION['counselor']['id'])) {
            header('Location: ../views/counselor_login.php');
            exit();
        }

        // Get counselor ID from session
        $counselorId = $_SESSION['counselor']['id'];

        // Fetch the logged-in counselor's profile
        $counselor = $this->counselorModel->getCounselorById($counselorId);
        
        // Get stats for the counselor
        $stats = $this->counselorModel->getCounselorStats($counselorId);

        // Check if profile was found
        if ($counselor) {
            ob_start();
            require_once '../views/counselling/counselor_profile.php'; // Pass data to the profile view
            $content = ob_get_clean();
            echo $content;
        } else {
            echo "Profile not found.";
        }
    }
    
    // Update counselor profile
    public function updateCounselorProfile() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if counselor is logged in
        if (!isset($_SESSION['counselor']['id'])) {
            header('Location: /GroupProject-IS2102/App/views/counselor_login.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $counselorId = $_SESSION['counselor']['id'];
            
            // Collect form data
            $data = [
                'name' => $_POST['name'],
                'type' => $_POST['type'],
                'specialization' => $_POST['specialization'],
                'description' => $_POST['description'],
                'email' => $_POST['email'],
                'username' => $_POST['username']
            ];

            // Perform basic validation
            if (empty($data['name']) || empty($data['email']) || empty($data['username'])) {
                $_SESSION['update_error'] = "Name, email, and username cannot be empty.";
                header('Location: /GroupProject-IS2102/App/controller/CounselorController.php?action=viewLoggedInCounselorProfile');
                exit();
            }

            // Update profile
            if ($this->counselorModel->updateCounselorProfile($counselorId, $data)) {
                $_SESSION['update_success'] = "Profile updated successfully.";
            } else {
                $_SESSION['update_error'] = "Failed to update profile.";
            }

            // Handle profile image upload if there's a file
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../../assets/images/counselors/';
                
                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = $counselorId . '_' . time() . '_' . basename($_FILES['profile_image']['name']);
                $uploadPath = $uploadDir . $fileName;
                
                // Check if the file is an image
                $fileType = strtolower(pathinfo($uploadPath, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                        // Update profile image path in database
                        $relativePath = 'assets/images/counselors/' . $fileName;
                        $this->counselorModel->updateProfileImage($counselorId, $relativePath);
                    } else {
                        $_SESSION['update_error'] = "Failed to upload profile image.";
                    }
                } else {
                    $_SESSION['update_error'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
                }
            }

            // Redirect back to profile page
            header('Location: /GroupProject-IS2102/App/controller/CounselorController.php?action=viewLoggedInCounselorProfile');
            exit();
        }
    }
    // Change password
public function changePassword() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if counselor is logged in
    if (!isset($_SESSION['counselor']['id'])) {
        header('Location: ../views/counselling/counselor_login.php');
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $counselorId = $_SESSION['counselor']['id'];
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Get counselor data to verify current password
        $counselor = $this->counselorModel->getCounselorById($counselorId);

        // Verify current password (plain text comparison since passwords aren't hashed yet)
        if ($currentPassword !== $counselor['password']) {
            $_SESSION['password_error'] = "Current password is incorrect.";
            header('Location: /GroupProject-IS2102/App/controller/CounselorController.php?action=viewLoggedInCounselorProfile');
            exit();
        }

        // Check if new password matches confirmation
        if ($newPassword !== $confirmPassword) {
            $_SESSION['password_error'] = "New password and confirmation do not match.";
            header('Location: /GroupProject-IS2102/App/controller/CounselorController.php?action=viewLoggedInCounselorProfile');
            exit();
        }

        // Check password strength
        if (strlen($newPassword) < 8) {
            $_SESSION['password_error'] = "Password must be at least 8 characters long.";
            header('Location: /GroupProject-IS2102/App/controller/CounselorController.php?action=viewLoggedInCounselorProfile');
            exit();
        }

        // Update password - store as plain text for now
        // Later you can modify this to use hashing
        if ($this->counselorModel->updatePlainPassword($counselorId, $newPassword)) {
            $_SESSION['password_success'] = "Password changed successfully.";
        } else {
            $_SESSION['password_error'] = "Failed to change password.";
        }

        header('Location: /GroupProject-IS2102/App/controller/CounselorController.php?action=viewLoggedInCounselorProfile');
        exit();
    }
}

    // Change password

}

// Handle the incoming request based on the 'action' and 'id' parameters
$controller = new CounselorController();
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

switch ($action) {
    case 'viewCounselor':
        if ($id > 0) {
            $controller->viewCounselor($id); // Show counselor's profile with reviews
        } else {
            $controller->listCounselors(); // List all counselors if no valid ID
        }
        break;
    case 'viewLoggedInCounselorProfile':
        $controller->viewLoggedInCounselorProfile(); // Show logged-in counselor's profile
        break;
    case 'updateCounselorProfile':
        $controller->updateCounselorProfile(); // Update counselor profile
        break;
    case 'changePassword':
        $controller->changePassword(); // Change counselor password
        break;
    default:
        $controller->listCounselors(); // List all counselors as default action
}
?>