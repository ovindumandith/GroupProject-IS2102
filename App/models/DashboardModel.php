<?php
require_once '../../config/config.php';
class DashboardModel {
    private $db;

    public function __construct() {
        
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    /**
     * Get count of academic questions submitted by the user
     * @param int $userId The user ID
     * @return int Number of academic questions
     */
    public function getAcademicQuestionsCount($userId) {
        try {
            $query = "SELECT COUNT(*) FROM academic_questions WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting academic questions: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count of appointments for the user
     * @param int $userId The user ID
     * @return int Number of appointments
     */
    public function getAppointmentsCount($userId) {
        try {
            $query = "SELECT COUNT(*) FROM appointments WHERE student_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting appointments: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count of completed appointments for the user
     * @param int $userId The user ID
     * @return int Number of completed appointments
     */
    public function getCompletedAppointmentsCount($userId) {
        try {
            $query = "SELECT COUNT(*) FROM appointments WHERE student_id = ? AND status = 'Accepted'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting completed appointments: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count of reviews written by the user
     * @param int $userId The user ID
     * @return int Number of reviews
     */
    public function getReviewsCount($userId) {
        try {
            $query = "SELECT COUNT(*) FROM reviews WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting reviews: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count of stress assessments taken by the user
     * @param int $userId The user ID
     * @return int Number of stress assessments
     */
    public function getStressAssessmentsCount($userId) {
        try {
            $query = "SELECT COUNT(*) FROM stress_assessment WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting stress assessments: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get latest stress level for the user
     * @param int $userId The user ID
     * @return string|null Latest stress level or null if not found
     */
    public function getLatestStressLevel($userId) {
        try {
            $query = "SELECT stress_level FROM stress_assessment 
                      WHERE user_id = ? 
                      ORDER BY assessment_date DESC 
                      LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['stress_level'] : null;
        } catch (PDOException $e) {
            $this->logError("Error getting latest stress level: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get a summary of all user activities
     * @param int $userId The user ID
     * @return array Summary of user activities
     */
    public function getUserActivitySummary($userId) {
        return [
            'academic_questions' => $this->getAcademicQuestionsCount($userId),
            'appointments' => $this->getAppointmentsCount($userId),
            'completed_appointments' => $this->getCompletedAppointmentsCount($userId),
            'reviews' => $this->getReviewsCount($userId),
            'stress_assessments' => $this->getStressAssessmentsCount($userId),
            'latest_stress_level' => $this->getLatestStressLevel($userId)
        ];
    }

    /**
     * Log errors to file
     * @param string $message Error message to log
     */
    private function logError($message) {
        $logDir = '../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/error_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}