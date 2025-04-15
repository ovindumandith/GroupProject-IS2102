<?php
require_once __DIR__ . '/../../config/config.php';

class CommentModel {
    private $db;
    private $table = 'comments';

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    public function getCommentsByPostId($postId) {
        try {
            $query = "SELECT c.*, u.username 
                      FROM {$this->table} c
                      JOIN users u ON c.user_id = u.user_id
                      WHERE c.post_id = :post_id
                      ORDER BY c.created_at DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function addComment($postId, $userId, $commentText) {
        try {
            $query = "INSERT INTO {$this->table} (post_id, user_id, comment_text, created_at)
                      VALUES (:post_id, :user_id, :comment_text, NOW())";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':comment_text', $commentText, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function getCommentCount($postId) {
        try {
            $query = "SELECT COUNT(*) FROM {$this->table} WHERE post_id = :post_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function getPostIdForComment($commentId) {
        $query = "SELECT post_id FROM comments WHERE comment_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$commentId]);
        return $stmt->fetchColumn();
    }
    
    public function deleteComment($commentId, $userId) {
        try {
            $query = "DELETE FROM comments 
                     WHERE comment_id = ? AND user_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$commentId, $userId]);
        } catch (PDOException $e) {
            error_log("Delete comment error: " . $e->getMessage());
            return false;
        }
    }
}
?>