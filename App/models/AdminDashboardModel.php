<?php
require_once '../../config/config.php';

class AdminDashboardModel {
    private $db;

    public function __construct() {
        
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    /**
     * Get total count of academic questions
     * @return int Total number of academic questions
     */
    public function getTotalAcademicQuestions() {
        try {
            $query = "SELECT COUNT(*) FROM academic_questions";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting academic questions: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count of pending academic questions
     * @return int Number of pending academic questions
     */
    public function getPendingAcademicQuestions() {
        try {
            $query = "SELECT COUNT(*) FROM academic_questions WHERE status = 'Pending'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting pending academic questions: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get total count of appointments
     * @return int Total number of appointments
     */
    public function getTotalAppointments() {
        try {
            $query = "SELECT COUNT(*) FROM appointments";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting appointments: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count of appointments by status
     * @param string $status The appointment status to count
     * @return int Number of appointments with the given status
     */
    public function getAppointmentsByStatus($status) {
        try {
            $query = "SELECT COUNT(*) FROM appointments WHERE status = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $status, PDO::PARAM_STR);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting appointments by status: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count of reviews
     * @return int Total number of reviews
     */
    public function getTotalReviews() {
        try {
            $query = "SELECT COUNT(*) FROM reviews";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting reviews: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get average rating from all reviews
     * @return float Average rating
     */
    public function getAverageRating() {
        try {
            $query = "SELECT AVG(rating) FROM reviews";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $avgRating = $stmt->fetchColumn();
            return $avgRating ? round((float)$avgRating, 1) : 0.0;
        } catch (PDOException $e) {
            $this->logError("Error calculating average rating: " . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Get total count of stress assessments
     * @return int Total number of stress assessments
     */
    public function getTotalStressAssessments() {
        try {
            $query = "SELECT COUNT(*) FROM stress_assessment";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting stress assessments: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count of stress assessments by stress level
     * @param string $level The stress level to count
     * @return int Number of stress assessments with the given level
     */
    public function getStressAssessmentsByLevel($level) {
        try {
            $query = "SELECT COUNT(*) FROM stress_assessment WHERE stress_level = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $level, PDO::PARAM_STR);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting stress assessments by level: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get distribution of stress levels
     * @return array Distribution of stress levels
     */
    public function getStressLevelDistribution() {
        return [
            'low' => $this->getStressAssessmentsByLevel('Low'),
            'moderate' => $this->getStressAssessmentsByLevel('Moderate'),
            'high' => $this->getStressAssessmentsByLevel('High')
        ];
    }

    /**
     * Get count of users by role
     * @param string $role The role to count
     * @return int Number of users with the given role
     */
    public function getUserCountByRole($role) {
        try {
            $query = "SELECT COUNT(*) FROM users WHERE role = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $role, PDO::PARAM_STR);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting users by role: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get distribution of users by role
     * @return array Distribution of users by role
     */
    public function getUserRoleDistribution() {
        return [
            'student' => $this->getUserCountByRole('student'),
            'admin' => $this->getUserCountByRole('admin'),
            'lecturer' => $this->getUserCountByRole('lecturer'),
            'hous' => $this->getUserCountByRole('hous'),
            'counselor' => $this->getTotalCounselors(),
            'super_admin' => $this->getUserCountByRole('super_admin')
        ];
    }

    /**
     * Get total number of counselors
     * @return int Total number of counselors
     */
    public function getTotalCounselors() {
        try {
            $query = "SELECT COUNT(*) FROM counselors";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting counselors: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get total count of users
     * @return int Total number of users
     */
    public function getTotalUsers() {
        try {
            $query = "SELECT COUNT(*) FROM users";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting users: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get appointments scheduled in the last 7 days
     * @return int Number of recent appointments
     */
    public function getRecentAppointments() {
        try {
            $query = "SELECT COUNT(*) FROM appointments WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting recent appointments: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get academic questions submitted in the last 7 days
     * @return int Number of recent academic questions
     */
    public function getRecentAcademicQuestions() {
        try {
            $query = "SELECT COUNT(*) FROM academic_questions WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting recent academic questions: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get stress assessments taken in the last 7 days
     * @return int Number of recent stress assessments
     */
    public function getRecentStressAssessments() {
        try {
            $query = "SELECT COUNT(*) FROM stress_assessment WHERE assessment_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting recent stress assessments: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get all dashboard metrics
     * @return array All dashboard metrics
     */
    public function getAllDashboardMetrics() {
        return [
            // Academic questions metrics
            'total_academic_questions' => $this->getTotalAcademicQuestions(),
            'pending_academic_questions' => $this->getPendingAcademicQuestions(),
            'recent_academic_questions' => $this->getRecentAcademicQuestions(),
            
            // Appointment metrics
            'total_appointments' => $this->getTotalAppointments(),
            'pending_appointments' => $this->getAppointmentsByStatus('Pending'),
            'accepted_appointments' => $this->getAppointmentsByStatus('Accepted'),
            'denied_appointments' => $this->getAppointmentsByStatus('Denied'),
            'recent_appointments' => $this->getRecentAppointments(),
            
            // Review metrics
            'total_reviews' => $this->getTotalReviews(),
            'average_rating' => $this->getAverageRating(),
            
            // Stress assessment metrics
            'total_stress_assessments' => $this->getTotalStressAssessments(),
            'stress_level_distribution' => $this->getStressLevelDistribution(),
            'recent_stress_assessments' => $this->getRecentStressAssessments(),
            
            // User metrics
            'total_users' => $this->getTotalUsers(),
            'user_role_distribution' => $this->getUserRoleDistribution()
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