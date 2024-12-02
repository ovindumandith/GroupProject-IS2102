<?php
require_once '../../config/config.php';

class LecturerModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

   public function getAllLecturers() {
        try {
            $query = 'SELECT * FROM lecturers ORDER BY created_at DESC';
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    private function logError($errorMessage) {
        $logFile = 'error_log.txt'; // Path to your error log file
        error_log($errorMessage, 3, $logFile);
    }
}

