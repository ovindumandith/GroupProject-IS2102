<?php
require_once __DIR__ . '/../../config/config.php'; // Adjust if needed

class CommentModel {
    private $conn;
    private $table = 'comments';

    public function __construct() {
        // Initialize Database object and get the connection using connect() method
        $database = new Database();
        $this->conn = $database->connect(); // Use connect() since your config.php uses that method
    }

    public function getCommentById($commentId) {
        $query = "SELECT * FROM $this->table WHERE comment_id = :comment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':comment_id', $commentId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateComment($commentId, $commentText) {
        $query = "UPDATE $this->table SET comment_text = :comment_text WHERE comment_id = :comment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':comment_text', $commentText);
        $stmt->bindParam(':comment_id', $commentId);

        return $stmt->execute(); // Returns true if successful, false otherwise
    }

    public function userOwnsComment($commentId, $userId) {
        $query = "SELECT * FROM $this->table WHERE comment_id = :comment_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':comment_id', $commentId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->rowCount() > 0; // Returns true if the user owns the comment
    }

    public function getPostIdForComment($commentId) {
        $query = "SELECT post_id FROM $this->table WHERE comment_id = :comment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':comment_id', $commentId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['post_id'] ?? null;
    }
}
?>
