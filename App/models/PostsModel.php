<?php
require_once '../../config/config.php';

class PostsModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    // Add a new post
    public function addPost($title, $description, $image, $userId) {
        try {
            $query = 'INSERT INTO posts (title, description, image, user_id, created_at) 
                      VALUES (:title, :description, :image, :user_id, NOW())';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Update an existing post
    public function updatePost($postId, $title, $description, $image) {
        try {
            $query = 'UPDATE posts 
                      SET title = :title, description = :description, image = :image 
                      WHERE post_id = :post_id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Delete a post
    public function deletePost($postId) {
        try {
            $query = 'DELETE FROM posts WHERE post_id = :post_id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Fetch all posts
    public function getAllPosts() {
        try {
            $query = 'SELECT p.*, u.username 
                      FROM posts p 
                      JOIN users u ON p.user_id = u.user_id 
                      ORDER BY p.created_at DESC';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return [];
        }
    }

    // Fetch a single post by ID
    public function getPostById($postId) {
        try {
            $query = 'SELECT * FROM posts WHERE post_id = :post_id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return null;
        }
    }

    private function logError($errorMessage) {
        $logFile = 'error_log.txt';
        error_log($errorMessage, 3, $logFile);
    }
}
?>
