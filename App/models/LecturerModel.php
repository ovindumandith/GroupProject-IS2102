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
        $query = 'SELECT * FROM lecturer ORDER BY created_at DESC';
        $stmt = $this->db->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            return []; // Return an empty array if no lecturers are found
        }
        return $result;
    } catch (PDOException $e) {
        $this->logError($e->getMessage());
        return []; // Return an empty array instead of false
    }
}




    private function logError($errorMessage) {
        $logFile = 'error_log.txt'; // Path to your error log file
        error_log($errorMessage, 3, $logFile);
    }
}

