<?php
// require_once 'C:\xampp\htdocs\GroupProject-IS2102\config\config.php';
require_once 'E:\xampp new\htdocs\GroupProject-IS2102\config\config.php';

class AdminStressAssessmentModel {
    private $db;

    public function __construct() {
        // Initialize and connect to the database
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    /**
     * Get all stress assessments for admin view
     * 
     * @return array All assessment records
     */
    public function getAllStressAssessments() {
        try {
            $query = 'SELECT sa.*, u.username as student_name, u.user_id 
                      FROM stress_assessment sa
                      JOIN users u ON sa.user_id = u.user_id
                      WHERE u.role = "student"
                      ORDER BY sa.assessment_date DESC';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    /**
     * Get a specific assessment by ID
     * 
     * @param int $assessmentId Assessment ID
     * @return array|bool The assessment record or false on failure
     */
    public function getAssessmentById($assessmentId) {
        try {
            $query = 'SELECT sa.*, u.name as student_name, u.email, u.user_id
                      FROM stress_assessment sa
                      JOIN users u ON sa.user_id = u.user_id
                      WHERE sa.assessment_id = :assessment_id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':assessment_id', $assessmentId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    /**
     * Get stress trend data for a specific student
     * 
     * @param int $userId User ID
     * @param int $limit Number of records to retrieve (default 10)
     * @return array Assessment records with stress levels
     */
    public function getStudentStressTrend($userId, $limit = 10) {
        try {
            $query = 'SELECT assessment_id, section1_score, section2_score, stress_level, assessment_date 
                    FROM stress_assessment 
                    WHERE user_id = :user_id 
                    ORDER BY assessment_date ASC 
                    LIMIT :limit';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    /**
     * Get high stress students for dashboard summary
     * 
     * @param int $limit Number of records to retrieve (default 5)
     * @return array Students with high stress
     */
    public function getHighStressStudents($limit = 5) {
        try {
            $query = 'SELECT sa.*, u.name as student_name
                      FROM stress_assessment sa
                      JOIN users u ON sa.user_id = u.user_id
                      WHERE sa.stress_level = "High"
                      AND sa.assessment_id IN (
                          SELECT MAX(assessment_id) 
                          FROM stress_assessment 
                          GROUP BY user_id
                      )
                      ORDER BY sa.assessment_date DESC
                      LIMIT :limit';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
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