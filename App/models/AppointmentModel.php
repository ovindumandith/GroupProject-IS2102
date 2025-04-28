<?php
require_once '../../config/config.php';

class AppointmentModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    // Create a new appointment
    public function createAppointment($studentId, $counselorId, $appointmentDate, $topic, $email, $phone) {
        $query = "INSERT INTO appointments (student_id, counselor_id, appointment_date, topic, email, phone) 
                  VALUES (:student_id, :counselor_id, :appointment_date, :topic, :email, :phone)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->bindParam(':counselor_id', $counselorId, PDO::PARAM_INT);
        $stmt->bindParam(':appointment_date', $appointmentDate, PDO::PARAM_STR);
        $stmt->bindParam(':topic', $topic, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Get appointments by student ID
    public function getByStudentId($studentId) {
        $query = "SELECT a.*, c.name AS counselor_name 
                  FROM appointments a
                  JOIN counselors c ON a.counselor_id = c.id
                  WHERE a.student_id = :student_id
                  ORDER BY a.appointment_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get appointments by counselor ID
    public function getByCounselorId($counselorId) {
        $query = "SELECT a.*, s.name AS student_name 
                  FROM appointments a
                  JOIN students s ON a.student_id = s.id
                  WHERE a.counselor_id = :counselor_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':counselor_id', $counselorId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPendingAppointmentsByCounselorId($counselorId) {
        $query = "SELECT id, student_id, appointment_date, topic, email, phone, created_at, updated_at, status 
                FROM appointments 
                WHERE counselor_id = :counselor_id AND status = 'Pending'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':counselor_id', $counselorId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get approved appointments by counselor ID
    public function getApprovedAppointmentsByCounselorId($counselorId) {
        $query = "SELECT id, student_id, appointment_date, topic, email, phone, created_at, updated_at, status 
                FROM appointments 
                WHERE counselor_id = :counselor_id AND status = 'Accepted'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':counselor_id', $counselorId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get denied appointments by counselor ID
    public function getDeniedAppointmentsByCounselorId($counselorId) {
        $query = "SELECT id, student_id, appointment_date, topic, email, phone, created_at, updated_at, status 
                FROM appointments 
                WHERE counselor_id = :counselor_id AND status = 'Denied'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':counselor_id', $counselorId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //update status of the appointment by counselor
    public function updateAppointmentStatus($appointmentId, $status) {
        $query = "UPDATE appointments SET status = :status WHERE id = :appointment_id ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteAppointment($appointmentId) {
        $query = "DELETE FROM appointments WHERE id = :appointment_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // In AppointmentModel.php
    public function getStudentNameById($studentId) {
        $query = "SELECT username FROM users WHERE user_id = :student_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['username'] : 'Unknown Student';
    }

    /**
     * Get student details by ID
     *
     * @param int $studentId The student ID
     * @return array|bool Student details or false if not found
     */
    public function getStudentDetails($studentId) {
        $query = "SELECT user_id, username, email, phone, year 
                FROM users 
                WHERE user_id = :student_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get appointment details by ID
     *
     * @param int $appointmentId The appointment ID
     * @return array|bool Appointment details or false if not found
     */
    public function getAppointmentById($appointmentId) {
        $query = "SELECT * FROM appointments WHERE id = :appointment_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get counselor details by ID
     *
     * @param int $counselorId The counselor ID
     * @return array|bool Counselor details or false if not found
     */
    public function getCounselorById($counselorId) {
        $query = "SELECT id, name, type, email, specialization 
                FROM counselors 
                WHERE id = :counselor_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':counselor_id', $counselorId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Update an appointment details by student
     * 
     * @param int $appointmentId The appointment ID
     * @param string $newDate The new appointment date and time
     * @param string $topic The updated topic
     * @param string $email The updated email
     * @param string $phone The updated phone
     * @return bool Whether the update was successful
     */
    public function updateStudentAppointment($appointmentId, $newDate, $topic, $email, $phone) {
        $query = "UPDATE appointments 
                SET appointment_date = :new_date, 
                    topic = :topic,
                    email = :email,
                    phone = :phone,
                    updated_at = NOW() 
                WHERE id = :appointment_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':new_date', $newDate, PDO::PARAM_STR);
        $stmt->bindParam(':topic', $topic, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Reschedule an appointment to a new date and time
     * 
     * @param int $appointmentId The appointment ID
     * @param string $newDate The new appointment date and time
     * @return bool Whether the update was successful
     */
    public function rescheduleAppointment($appointmentId, $newDate) {
        $query = "UPDATE appointments SET appointment_date = :new_date, updated_at = NOW() WHERE id = :appointment_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':new_date', $newDate, PDO::PARAM_STR);
        $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Get all counseling appointments
     */
    public function getAllAppointments() {
        try {
            $query = "SELECT a.*, 
                      s.username as student_name, 
                      c.name as counselor_name, 
                      c.type as counselor_type
                      FROM appointments a
                      JOIN users s ON a.student_id = s.user_id
                      JOIN counselors c ON a.counselor_id = c.id
                      ORDER BY a.appointment_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Get appointments by status
     */
    public function getAppointmentsByStatus($status) {
        try {
            $query = "SELECT a.*, 
                      s.username as student_name, 
                      c.name as counselor_name, 
                      c.type as counselor_type
                      FROM appointments a
                      JOIN users s ON a.student_id = s.user_id
                      JOIN counselors c ON a.counselor_id = c.id
                      WHERE a.status = :status
                      ORDER BY a.appointment_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Get detailed appointment information by ID for admin view
     */
    public function getDetailedAppointmentById($appointmentId) {
        try {
            $query = "SELECT a.*, 
                      s.username as student_name, s.email as student_email, s.phone as student_phone,
                      c.name as counselor_name, c.email as counselor_email, c.type as counselor_type
                      FROM appointments a
                      JOIN users s ON a.student_id = s.user_id
                      JOIN counselors c ON a.counselor_id = c.id
                      WHERE a.id = :appointment_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Get appointment count by status
     */
    public function getAppointmentCountsByStatus() {
        try {
            $query = "SELECT 
                      SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                      SUM(CASE WHEN status = 'Accepted' THEN 1 ELSE 0 END) as accepted_count,
                      SUM(CASE WHEN status = 'Denied' THEN 1 ELSE 0 END) as denied_count,
                      COUNT(*) as total_count
                      FROM appointments";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [
                'pending_count' => 0,
                'accepted_count' => 0,
                'denied_count' => 0,
                'total_count' => 0
            ];
        }
    }

    /**
     * Get appointment statistics for admin dashboard
     */
    public function getAppointmentStatistics() {
        try {
            // Get counts by status
            $statusCounts = $this->getAppointmentCountsByStatus();
            
            // Get counts by counselor type
            $typeQuery = "SELECT 
                          c.type as counselor_type,
                          COUNT(*) as count
                          FROM appointments a
                          JOIN counselors c ON a.counselor_id = c.id
                          GROUP BY c.type";
            $typeStmt = $this->db->prepare($typeQuery);
            $typeStmt->execute();
            $typeCounts = $typeStmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Get counts by topic (top 5)
            $topicQuery = "SELECT 
                           topic,
                           COUNT(*) as count
                           FROM appointments
                           GROUP BY topic
                           ORDER BY count DESC
                           LIMIT 5";
            $topicStmt = $this->db->prepare($topicQuery);
            $topicStmt->execute();
            $topTopics = $topicStmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Get appointments per day (last 7 days)
            $dateQuery = "SELECT 
                          DATE(appointment_date) as date,
                          COUNT(*) as count
                          FROM appointments
                          WHERE appointment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                          GROUP BY DATE(appointment_date)
                          ORDER BY date";
            $dateStmt = $this->db->prepare($dateQuery);
            $dateStmt->execute();
            $dateStats = $dateStmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Return combined statistics
            return [
                'status_counts' => $statusCounts,
                'type_counts' => $typeCounts,
                'top_topics' => $topTopics,
                'date_stats' => $dateStats
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Search appointments by query
     */
    public function searchAppointments($query) {
        try {
            $searchQuery = "SELECT a.*, 
                           s.username as student_name, 
                           c.name as counselor_name, 
                           c.type as counselor_type
                           FROM appointments a
                           JOIN users s ON a.student_id = s.user_id
                           JOIN counselors c ON a.counselor_id = c.id
                           WHERE s.username LIKE :query 
                           OR c.name LIKE :query 
                           OR a.topic LIKE :query 
                           OR a.email LIKE :query
                           ORDER BY a.appointment_date DESC";
            $stmt = $this->db->prepare($searchQuery);
            $searchParam = '%' . $query . '%';
            $stmt->bindParam(':query', $searchParam, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Get upcoming appointments for admin dashboard
     */
    public function getUpcomingAppointments($limit = 5) {
        try {
            $query = "SELECT a.*, 
                      s.username as student_name, 
                      c.name as counselor_name, 
                      c.type as counselor_type
                      FROM appointments a
                      JOIN users s ON a.student_id = s.user_id
                      JOIN counselors c ON a.counselor_id = c.id
                      WHERE a.appointment_date >= NOW() AND a.status = 'Accepted'
                      ORDER BY a.appointment_date ASC
                      LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Export appointments to CSV format
     */
    public function exportAppointmentsToCSV() {
        try {
            $query = "SELECT a.id, s.username as student_name, c.name as counselor_name, 
                      c.type as counselor_type, a.topic, a.appointment_date, 
                      a.email, a.phone, a.status, a.created_at
                      FROM appointments a
                      JOIN users s ON a.student_id = s.user_id
                      JOIN counselors c ON a.counselor_id = c.id
                      ORDER BY a.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}