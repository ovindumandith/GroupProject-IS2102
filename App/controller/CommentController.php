<?php
require_once __DIR__ . '/../models/CommentModel.php';

class CommentController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new CommentModel();
    }

    public function handleDelete() {
        session_start();
        
        // Verify user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /GroupProject-IS2102/login.php');
            exit();
        }

        // Get comment ID from URL
        $commentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        // Delete comment
        if ($commentId > 0) {
            $success = $this->commentModel->deleteComment($commentId, $_SESSION['user_id']);
            
            // Redirect back to previous page
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        die("Invalid comment ID");
    }
}

// Instantiate and execute
$controller = new CommentController();
$controller->handleDelete();