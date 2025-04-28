<?php
require_once __DIR__ . '/../../config/config.php'; 

class Notification {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function sendNotification($userId, $postId, $reason) {
        try {
            // First get post title
            $postStmt = $this->conn->prepare("SELECT title FROM posts WHERE post_id = ?");
            $postStmt->execute([$postId]);
            $post = $postStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$post) return false;

            // Begin transaction
            $this->conn->beginTransaction();

            // Insert notification
            $stmt = $this->conn->prepare("INSERT INTO notifications (user_id, post_id, title, reason) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $postId, $post['title'], $reason]);

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
            $stmt = $this->conn->query("
                SELECT n.*, p.title, p.user_id 
                FROM notifications n
                LEFT JOIN posts p ON n.post_id = p.post_id
                ORDER BY n.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching notifications: " . $e->getMessage());
            return [];
        }
    }

    public function fetchNotificationsByUser($userId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM notifications 
                WHERE user_id = ? 
                ORDER BY created_at DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user notifications: " . $e->getMessage());
            return [];
        }
    }
    
    public function deleteNotification($notificationId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM notifications WHERE notification_id = ?");
            return $stmt->execute([$notificationId]);
        } catch (PDOException $e) {
            error_log("Error deleting notification: " . $e->getMessage());
            return false;
        }
    }

    public function updateNotification($notificationId, $reason, $title) {
        try {
            $stmt = $this->conn->prepare("UPDATE notifications SET reason = ?, title = ? WHERE notification_id = ?");
            return $stmt->execute([$reason, $title, $notificationId]);
        } catch (PDOException $e) {
            error_log("Error updating notification: " . $e->getMessage());
            return false;
        }
    }
    
    
    public function getNotificationById($notificationId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT n.*, p.user_id 
                FROM notifications n
                LEFT JOIN posts p ON n.post_id = p.post_id
                WHERE n.notification_id = ?
            ");
            $stmt->execute([$notificationId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching notification by ID: " . $e->getMessage());
            return false;
        }
    }
    
    public function getPostInfo($postId) {
        try {
            $stmt = $this->conn->prepare("SELECT user_id, title FROM posts WHERE post_id = ?");
            $stmt->execute([$postId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching post info: " . $e->getMessage());
            return false;
        }
    }
}
?>