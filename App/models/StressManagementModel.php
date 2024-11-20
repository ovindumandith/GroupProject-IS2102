<?php
require_once '../../config/config.php';

class StressManagementModel {
    private $db;

    public function __construct() {
        // Initialize and connect to the database
        $this->db = new Database();
        $this->db = $this->db->connect(); // Assuming the connect method returns a PDO instance
    }

    // Save user's stress management responses
    public function saveStressData($userId, $sleepHours, $exerciseHours, $workHours, $moodStatus) {
        try {
            $query = 'INSERT INTO stress_management_responses 
                      (user_id, sleep_hours, exercise_hours, work_hours, mood_status) 
                      VALUES (:user_id, :sleep_hours, :exercise_hours, :work_hours, :mood_status)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':sleep_hours', $sleepHours, PDO::PARAM_INT);
            $stmt->bindParam(':exercise_hours', $exerciseHours, PDO::PARAM_INT);
            $stmt->bindParam(':work_hours', $workHours, PDO::PARAM_INT);
            $stmt->bindParam(':mood_status', $moodStatus, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Retrieve stress records for a user
    public function getStressRecords($userId, $limit = 10, $offset = 0) {
        try {
            $query = 'SELECT * FROM stress_management_responses 
                      WHERE user_id = :user_id 
                      ORDER BY response_date DESC
                      LIMIT :limit OFFSET :offset';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Log database errors
    private function logError($errorMessage) {
        $logFile = '../../logs/error_log.txt'; // Ensure this path exists
        error_log($errorMessage, 3, $logFile);
    }
}
?>
