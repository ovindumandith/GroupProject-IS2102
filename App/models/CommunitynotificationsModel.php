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

    public function fetchNotificationsByUser($user_id) {
        $query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteNoti($notiId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM notifications WHERE notification_id = ?");
            $stmt->execute([$notiId]);
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting event: " . $e->getMessage());
            return false;
        }
    }

    public function updateNotification($id, $title, $reason) {
        try {
            $stmt = $this->conn->prepare("UPDATE notifications SET title = ?, reason = ? WHERE notification_id = ?");
            $stmt->execute([$title, $reason, $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Error updating notification: " . $e->getMessage());
            return false;
        }
    }
    
    public function fetchNotificationById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM notifications WHERE notification_id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching notification by ID: " . $e->getMessage());
            return false;
        }
    }
    
}
?>
