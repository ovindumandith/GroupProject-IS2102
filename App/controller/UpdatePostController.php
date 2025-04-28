<?php
require_once '../models/PostsModel.php';

class UpdatePostController {
    private $model;

    public function __construct() {
        $this->model = new PostsModel();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? $_POST['action'] ?? null;
    
        switch ($action) {
            case 'edit':
                $this->loadEditForm();
                break;
            case 'update':
                $this->updatePost();
                break;
            default:
                echo "<script>alert('Invalid action.'); window.location='../views/manage_post.php';</script>";
        }
    }

    private function loadEditForm() {
        $postId = $_GET['post_id'] ?? null;

        if ($postId && is_numeric($postId)) {
            $post = $this->model->getPostById(intval($postId));

            if ($post) {
                include '../views/edit_post.php';
            } else {
                echo "<script>alert('Post not found.'); window.location='../views/manage_post.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid post ID.'); window.location='../views/manage_post.php';</script>";
        }
    }

    private function updatePost() {
        $postId = intval($_POST['post_id']);
        $title = $_POST['title'];
        $description = $_POST['description'];
        $oldImage = $_POST['old_image'] ?? null;
        $uploadDir = '/GroupProject-IS2102/App/views/uploads/';

        // Handle new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpName = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $imageName;

            // Move uploaded image
            if (move_uploaded_file($imageTmpName, $targetPath)) {
                $image = $imageName;
            } else {
                $image = $oldImage; // Fallback to old image if upload fails
            }
        } else {
            $image = $oldImage; // Use old image if no new image uploaded
        }

        if ($this->model->updatePost($postId, $title, $description, $image)) {
            header("Location: ../views/edit_post.php?post_id=$postId&status=success");
            exit();

        } else {
            echo "<script>alert('Failed to update post.'); window.location='../views/manage_post.php';</script>";
        }
    }
}

$controller = new UpdatePostController();
$controller->handleRequest();
?>
