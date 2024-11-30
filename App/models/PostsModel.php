<?php
require_once '../../config/config.php';

class PostsModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    public function getAllPosts() {
        $query = "SELECT p.post_id, p.title, p.description, p.image, p.created_at, u.username 
                  FROM posts p 
                  JOIN users u ON p.user_id = u.user_id
                  ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch posts by user ID
    public function getPostsByUserId($userId) {
        $sql = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPost($userId, $title, $image, $description) {
        $query = "INSERT INTO posts (user_id, title, image, description, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindValue(1, $userId);
        $stmt->bindValue(2, $title);
        $stmt->bindValue(3, $image);
        $stmt->bindValue(4, $description);

        // Execute the query
        return $stmt->execute();
    }

    // Delete Post
public function deletePost($postId) {
    try {
        $query = 'DELETE FROM posts WHERE post_id = :post_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        return false;
    }
}

public function getPostById($postId) {
    try {
        $query = 'SELECT * FROM posts WHERE post_id = :post_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        return false;
    }
}

public function updatePost($postId, $title, $description, $image) {
    try {
        $query = 'UPDATE posts SET title = :title, description = :description, image = :image WHERE post_id = :post_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        return false;
    }
}




}
