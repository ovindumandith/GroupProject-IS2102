<?php
require_once '../models/PostsModel.php';

class CreateController {
    private $model;

    public function __construct() {
        $this->model = new PostsModel();
    }

    public function addPost() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo "Request method received: " . $_SERVER['REQUEST_METHOD'];
                throw new Exception("Invalid request method. Expected POST.");
            }

            // Sanitize input
            $userId = htmlspecialchars($_POST['user_id']);
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $image = null;

            // Handle file upload
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "../Postuploads/";
                $imageName = basename($_FILES['image']['name']);
                $targetFilePath = $targetDir . $imageName;

                // Validate file type
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception("Invalid file type. Allowed types: JPG, JPEG, PNG, GIF.");
                }

                // Ensure the directory exists and is writable
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                // Move the file to the target directory
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    throw new Exception("Failed to upload the file.");
                }

                $image = $imageName;
            }

            // Add the post to the database
            if ($this->model->addPost($userId, $title, $image, $description)) {
                header("Location: ../views/community_index.php");
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
