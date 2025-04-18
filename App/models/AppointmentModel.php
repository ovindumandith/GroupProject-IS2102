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
                  WHERE a.student_id = :student_id";
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


    

}


?>