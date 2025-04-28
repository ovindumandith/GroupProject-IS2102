<?php
require_once 'C:\xampp\htdocs\GroupProject-IS2102\config\config.php';

class CounselorDashboardModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    /**
     * Get total count of appointments for a specific counselor
     * @param int $counselorId The counselor ID
     * @return int Total number of appointments
     */
    public function getTotalAppointments($counselorId) {
        try {
            $query = "SELECT COUNT(*) FROM appointments WHERE counselor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting appointments: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count of appointments by status for a specific counselor
     * @param int $counselorId The counselor ID
     * @param string $status The appointment status to count
     * @return int Number of appointments with the given status
     */
    public function getAppointmentsByStatus($counselorId, $status) {
        try {
            $query = "SELECT COUNT(*) FROM appointments WHERE counselor_id = ? AND status = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->bindParam(2, $status, PDO::PARAM_STR);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting appointments by status: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get upcoming appointments for a specific counselor
     * @param int $counselorId The counselor ID
     * @param int $limit The maximum number of appointments to return
     * @return array List of upcoming appointments
     */
    public function getUpcomingAppointments($counselorId, $limit = 5) {
        try {
            $query = "SELECT a.*, u.username as student_name 
                      FROM appointments a 
                      JOIN users u ON a.student_id = u.user_id
                      WHERE a.counselor_id = ? AND a.status = 'Accepted' 
                      AND a.appointment_date >= NOW()
                      ORDER BY a.appointment_date ASC
                      LIMIT ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->bindParam(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Error fetching upcoming appointments: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get recent appointments for a specific counselor
     * @param int $counselorId The counselor ID
     * @param int $limit The maximum number of appointments to return
     * @return array List of recent appointments
     */
    public function getRecentAppointments($counselorId, $limit = 5) {
        try {
            $query = "SELECT a.*, u.username as student_name 
                      FROM appointments a 
                      JOIN users u ON a.student_id = u.user_id
                      WHERE a.counselor_id = ?
                      ORDER BY a.created_at DESC
                      LIMIT ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->bindParam(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Error fetching recent appointments: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get pending appointments for a specific counselor
     * @param int $counselorId The counselor ID
     * @param int $limit The maximum number of appointments to return
     * @return array List of pending appointments
     */
    public function getPendingAppointments($counselorId, $limit = 5) {
        try {
            $query = "SELECT a.*, u.username as student_name 
                      FROM appointments a 
                      JOIN users u ON a.student_id = u.user_id
                      WHERE a.counselor_id = ? AND a.status = 'Pending'
                      ORDER BY a.appointment_date ASC
                      LIMIT ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->bindParam(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Error fetching pending appointments: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get count of reviews for a specific counselor
     * @param int $counselorId The counselor ID
     * @return int Number of reviews
     */
    public function getTotalReviews($counselorId) {
        try {
            $query = "SELECT COUNT(*) FROM reviews WHERE counselor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting reviews: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get average rating for a specific counselor
     * @param int $counselorId The counselor ID
     * @return float Average rating
     */
    public function getAverageRating($counselorId) {
        try {
            $query = "SELECT AVG(rating) FROM reviews WHERE counselor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->execute();
            $avgRating = $stmt->fetchColumn();
            return $avgRating ? round((float)$avgRating, 1) : 0.0;
        } catch (PDOException $e) {
            $this->logError("Error calculating average rating: " . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Get recent reviews for a specific counselor
     * @param int $counselorId The counselor ID
     * @param int $limit The maximum number of reviews to return
     * @return array List of recent reviews
     */
    public function getRecentReviews($counselorId, $limit = 5) {
        try {
            $query = "SELECT r.*, u.username as student_name 
                      FROM reviews r 
                      JOIN users u ON r.user_id = u.user_id
                      WHERE r.counselor_id = ?
                      ORDER BY r.created_at DESC
                      LIMIT ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->bindParam(2, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Error fetching recent reviews: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get appointment statistics for a specific counselor for last 6 months
     * @param int $counselorId The counselor ID
     * @return array Monthly appointment statistics
     */
    public function getMonthlyAppointmentStats($counselorId) {
        try {
            $query = "SELECT 
                        DATE_FORMAT(appointment_date, '%Y-%m') as month,
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN status = 'Accepted' THEN 1 ELSE 0 END) as accepted,
                        SUM(CASE WHEN status = 'Denied' THEN 1 ELSE 0 END) as denied
                      FROM appointments
                      WHERE counselor_id = ? 
                      AND appointment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                      GROUP BY DATE_FORMAT(appointment_date, '%Y-%m')
                      ORDER BY month ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Error fetching monthly appointment stats: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all dashboard metrics
     * @param int $counselorId The counselor ID
     * @return array All dashboard metrics
     */
    public function getAllDashboardMetrics($counselorId) {
        return [
            // Appointment metrics
            'total_appointments' => $this->getTotalAppointments($counselorId),
            'pending_appointments' => $this->getAppointmentsByStatus($counselorId, 'Pending'),
            'accepted_appointments' => $this->getAppointmentsByStatus($counselorId, 'Accepted'),
            'denied_appointments' => $this->getAppointmentsByStatus($counselorId, 'Denied'),
            'upcoming_appointments' => $this->getUpcomingAppointments($counselorId),
            'recent_appointments' => $this->getRecentAppointments($counselorId),
            'pending_appointment_list' => $this->getPendingAppointments($counselorId),
            
            // Review metrics
            'total_reviews' => $this->getTotalReviews($counselorId),
            'average_rating' => $this->getAverageRating($counselorId),
            'recent_reviews' => $this->getRecentReviews($counselorId),
            
            // Statistics
            'monthly_stats' => $this->getMonthlyAppointmentStats($counselorId)
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