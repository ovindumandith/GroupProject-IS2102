<?php
require_once '../models/PostsModel.php';

class CommunityController {
    private $model;

    public function __construct() {
        $this->model = new PostsModel();
    }

    public function displayPosts() {
        try {
            // Start session if not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $posts = $this->model->getAllPosts();
            
            // Add base path to images for display
            foreach ($posts as &$post) {
                if (!empty($post['image'])) {
                    // Use relative path from the root of your application
                    $post['image_path'] = '/GroupProject-IS2102/App/' . $post['image'];
                } else {
                    $post['image_path'] = null;
                }
            }
            
            // Unset the reference variable
            unset($post);
            
            require '../views/community_index.php';
        } catch (PDOException $e) {
            error_log("Display Error: " . $e->getMessage());
            
            // Set error message in session
            $_SESSION['error'] = "Failed to load posts. Please try again later.";
            
            // Redirect to error page or back to community with error
            header('Location: ../views/error.php');
            exit();
        }
    }
}

// Instantiate and call the controller
$controller = new CommunityController();
$controller->displayPosts();
?>