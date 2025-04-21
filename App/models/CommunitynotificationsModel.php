<?php
require_once __DIR__ . '/../../config/config.php'; 

class Notification {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function sendNotification($userId, $postId, $title, $reason) {
        try {
            // Begin transaction
            $this->conn->beginTransaction();

            // Insert notification
            $stmt = $this->conn->prepare("INSERT INTO notifications (user_id, post_id, title, reason) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $postId, $title, $reason]);

            // Delete post from posts table
            $deleteStmt = $this->conn->prepare("DELETE FROM posts WHERE post_id = ?");
            $deleteStmt->execute([$postId]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error sending notification: " . $e->getMessage());
            return false;
        }
    }

    public function fetchAllNotifications() {
        try {
            $stmt = $this->conn->query("SELECT * FROM notifications ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching notifications: " . $e->getMessage());
            return [];
        }
    }

    public function fetchNotificationsByUserId($userId) {
        $conn = $this->connect(); // Assuming this is how you connect to DB
    
        $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ?");
        $stmt->execute([$userId]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
?>
