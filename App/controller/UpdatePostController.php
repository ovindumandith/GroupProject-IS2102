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
        $image = $_POST['image'] ?? NULL;

        if ($this->model->updatePost($postId, $title, $description, $image)) {
            echo "<script>alert('Post updated successfully.'); window.location='../views/manage_post.php';</script>";
        } else {
            echo "<script>alert('Failed to update post.'); window.location='../views/manage_post.php';</script>";
        }
    }
}

// Instantiate and handle request
$controller = new UpdatePostController();
$controller->handleRequest();
?>
