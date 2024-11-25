<?php

require_once '../../config/config.php';

class ViewRelaxationActivityModel {

    private $db;

    public function __construct() {
        // Initialize and connect to the database
        $this->db = new Database();
        $this->db = $this->db->connect(); // Assuming the connect method returns a PDO instance
    }

    public function getAllActivities() {
        try {
            $sql = "SELECT * FROM relaxation_activities";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all results as an array
        } catch (Exception $e) {
            // Handle exception
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function getUserRole($userId) {
        // Logic to fetch the user role from the database
        // Assuming the user role is stored in a table called 'users' and the column name is 'role'
        try {
            $sql = "SELECT role FROM users WHERE user_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['role'];
        } catch (Exception $e) {
            // Handle exception
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function updateActivity($activityId, $activityName, $description, $imageUrl, $playlistUrl) {

        try {
            $sql = "UPDATE relaxation_activities SET activity_name = :activity_name, description = :description, image_url = :image_url, playlist_url = :playlist_url WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':activity_name', $activityName);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':image_url', $imageUrl);
            $stmt->bindParam(':playlist_url', $playlistUrl);
            $stmt->bindParam(':id', $activityId);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            // Handle exception
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function deleteActivity($activityId) {
        // Logic to delete an activity from the database
        try {
            $sql = "DELETE FROM relaxation_activities WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $activityId);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            // Handle exception
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


}

?>
