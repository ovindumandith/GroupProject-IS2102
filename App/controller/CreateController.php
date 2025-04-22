<?php
require_once '../models/PostsModel.php';

class CreateController {
    private $model;

    public function __construct() {
        $this->model = new PostsModel();
        session_start();
    }

    public function addPost() {
        try {
            // Verify POST request
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo "Request method received: " . $_SERVER['REQUEST_METHOD'];
                throw new Exception("Invalid request method. Expected POST.");
            }

            // Sanitize input
            $userId = htmlspecialchars($_POST['user_id']);
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $image = null;

            $image = null;
$imagePath = null;

if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '/Applications/XAMPP/xamppfiles/htdocs/GroupProject-IS2102/App/views/uploads/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileInfo = pathinfo($_FILES['image']['name']);
    $extension = strtolower($fileInfo['extension']);
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif','pdf'];

    if (!in_array($extension, $allowedTypes)) {
        throw new Exception("Only JPG, PNG, GIF, PDF files are allowed");
    }

    if ($_FILES['image']['size'] > 10000000) {
        throw new Exception("File too large (max 10MB)");
    }

    $filename = uniqid('post_', true).'.'.$extension;
    $destination = $uploadDir.$filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        $imagePath = 'views/uploads/'.$filename;
        $image = $imagePath; // ✅ Assign here
    } else {
        $error = error_get_last();
        throw new Exception("File upload failed: ".$error['message']);
    }
}

            // Add the post to the database
            if ($this->model->addPost($userId, $title, $image, $description)) {
                header("Location: ../views/create_post.php");
                exit();
            } else {
                throw new Exception("Failed to create the post. Please try again.");
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'addPost') {
    echo "Request method: " . $_SERVER['REQUEST_METHOD']; // Debugging line
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new CreateController();
        $controller->addPost();
    } else {
        echo "Invalid request method. Expected POST.";
    }
} else {
    echo "Invalid or missing action.";
}

?>
