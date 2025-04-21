<?php
require_once __DIR__ . '/../../config/config.php'; 

class Event {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function addEvent($title, $date, $link, $description, $category) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO events (title, date, link, description, category) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $date, $link, $description, $category]);
            return true;
        } catch (PDOException $e) {
            error_log("Error adding event: " . $e->getMessage());
            return false;
        }
    }

    public function updateEvent($eventId, $title, $date, $link, $description, $category) {
        try {
            $stmt = $this->conn->prepare("UPDATE events SET title = ?, date = ?, link = ?, description = ?, category = ? WHERE event_id = ?");
            $stmt->execute([$title, $date, $link, $description, $category, $eventId]);
            return true;
        } catch (PDOException $e) {
            error_log("Error updating event: " . $e->getMessage());
            return false;
        }
    }

    public function deleteEvent($eventId) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM events WHERE event_id = ?");
            $stmt->execute([$eventId]);
            return true;
        } catch (PDOException $e) {
            error_log("Error deleting event: " . $e->getMessage());
            return false;
        }
    }

    public function fetchAllEvents() {
        try {
            $stmt = $this->conn->query("SELECT * FROM events ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching events: " . $e->getMessage());
            return [];
        }
    }

}
?>
