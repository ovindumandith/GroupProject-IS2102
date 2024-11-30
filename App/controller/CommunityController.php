<?php
require_once '../models/PostsModel.php';

class CommunityController {
    private $model;

    public function __construct() {
        $this->model = new PostsModel();
    }

    public function displayPosts() {
        $posts = $this->model->getAllPosts();
        require '../views/community_index.php';
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->addPost();
        }
    }

}

// Handle request
$controller = new CommunityController();
$controller->displayPosts();


?>
