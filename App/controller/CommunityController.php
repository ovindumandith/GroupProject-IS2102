<?php
require_once '../models/PostsModel.php';

class CommunityController {
    private $model;

    public function __construct() {
        $this->model = new PostsModel();
    }

<<<<<<< HEAD
    // Add a new post
    public function addPost($title, $description, $image, $userId) {
        if ($this->postsModel->addPost($title, $description, $image, $userId)) {
            header('Location: ../views/community/index.php?message=Post added successfully'); // Redirect after success
            exit();
        } else {
            echo "Error adding post.";
=======
    public function displayPosts() {
        $posts = $this->model->getAllPosts();
        require '../views/community_index.php';
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->addPost();
>>>>>>> 7853f98 (Complete Crud and Academic Help)
        }
    }

}

// Handle request
$controller = new CommunityController();
$controller->displayPosts();


?>
