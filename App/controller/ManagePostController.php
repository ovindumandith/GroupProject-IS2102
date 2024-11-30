<?php

require_once '../models/PostsModel.php'; // Include the PostsModel class

class ManagePostController {
    private $model;

    public function __construct() {
        $this->model = new PostsModel(); // Initialize the PostsModel
    }

    // Handle the request
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete'])) {
                $this->handleDelete();
            } elseif (isset($_POST['edit'])) {
                $this->handleEdit();
            } elseif (isset($_POST['update'])) {
                $this->handleUpdate();
            }
        }

        // Always display posts after handling the request
        $this->displayPosts();
    }

    private function handleDelete() {
        if (isset($_POST['post_id']) && is_numeric($_POST['post_id'])) {
            $postId = intval($_POST['post_id']);
            if ($postId > 0) {
                $isDeleted = $this->model->deletePost($postId);
                if ($isDeleted) {
                    echo "<script>alert('Post deleted successfully.'); window.location = '../views/manage_post.php';</script>";
                } else {
                    echo "<script>alert('Failed to delete the post. Please try again.');</script>";
                }
            } else {
                echo "<script>alert('Invalid post ID.');</script>";
            }
        }
    }

    

    public function displayPosts() {
        $userId = 1; // Example user ID (replace with dynamic user session ID)
        $posts = $this->model->getPostsByUserId($userId); 
        require '../views/manage_post.php'; // Load the view and pass the posts data
    }
}

// Handle the request
$controller = new ManagePostController();
$controller->handleRequest();
