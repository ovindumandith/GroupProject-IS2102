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
}

?>
