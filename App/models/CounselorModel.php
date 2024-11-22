<?php
require_once '../../config/config.php';

class CounselorModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    // Fetch all counselors
    public function getAllCounselors() {
        try {
            $query = 'SELECT * FROM counselors';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return [];
        }
    }

    // Fetch a single counselor by ID
public function getCounselorById($id) {
    try {
        $query = 'SELECT * FROM counselors WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $counselor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($counselor) {
            return $counselor;
        } else {
            return null; // If no counselor is found
        }
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
