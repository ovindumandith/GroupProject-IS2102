<?php
require_once '../models/PostsModel.php';

class CommunityController {
    private $postsModel;

    public function __construct() {
        $this->postsModel = new PostsModel();
    }

    // Add a new post
    public function addPost($title, $description, $image, $userId) {
        if ($this->postsModel->createPost($title, $description, $image, $userId)) {
            header('Location: ../views/community/index.php?message=Post added successfully'); // Redirect after success
            exit();
        } else {
            echo "Error adding post.";
        }
    }

    // Update an existing post
    public function updatePost($postId, $title, $description, $image = null) {
        if ($this->postsModel->updatePost($postId, $title, $description, $image)) {
            header('Location: ../views/community/index.php?message=Post updated successfully'); // Redirect after success
            exit();
        } else {
            echo "Error updating post.";
        }
    }

    // Delete a post
    public function deletePost($postId) {
        if ($this->postsModel->deletePost($postId)) {
            header('Location: ../views/community/index.php?message=Post deleted successfully'); // Redirect after success
            exit();
        } else {
            echo "Error deleting post.";
        }
    }
}

// Handle incoming requests based on the action parameter
$controller = new CommunityController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    switch ($action) {
        case 'addPost':
            $title = $_POST['title'];
            $description = $_POST['description'];
            $userId = $_POST['user_id'];
            $image = $_FILES['image']['name'];

            // Handle image upload if provided
            if ($image) {
                $targetDir = "../../uploads/";
                $targetFile = $targetDir . basename($image);
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            }

            $controller->addPost($title, $description, $image, $userId);
            break;

        case 'updatePost':
            $postId = $_POST['post_id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $image = $_FILES['image']['name'];

            // Handle image upload if provided
            if ($image) {
                $targetDir = "../../uploads/";
                $targetFile = $targetDir . basename($image);
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            }

            $controller->updatePost($postId, $title, $description, $image);
            break;

        case 'deletePost':
            $postId = $_POST['post_id'];
            $controller->deletePost($postId);
            break;

        default:
            echo "Invalid action.";
    }
} else {
    echo "Invalid request method.";
}
?>
