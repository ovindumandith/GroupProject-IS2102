<?php
require_once '../../config/config.php';

class AcademicQuestionModel {
    private $db;

    public function __construct() {
        // Initialize and connect to the database
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    // Submit a new academic question
    public function submitQuestion($userId, $indexNo, $regNo, $fullName, $faculty, $telephone, $email, $question) {
        try {
            $query = 'INSERT INTO academic_questions 
                      (user_id, index_no, reg_no, full_name, faculty, telephone, email, question, status, created_at) 
                      VALUES 
                      (:user_id, :index_no, :reg_no, :full_name, :faculty, :telephone, :email, :question, "pending", NOW())';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':index_no', $indexNo);
            $stmt->bindParam(':reg_no', $regNo);
            $stmt->bindParam(':full_name', $fullName);
            $stmt->bindParam(':faculty', $faculty);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':question', $question);

            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Fetch all questions (for administrators or head of undergraduate studies)
    public function getAllQuestions() {
        try {
            $query = 'SELECT * FROM academic_questions ORDER BY created_at DESC';
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Fetch questions submitted by a specific user
    public function getUserQuestions($userId) {
        try {
            $query = 'SELECT * FROM academic_questions WHERE user_id = :user_id ORDER BY created_at DESC';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Get a specific question by ID
    public function getQuestionById($questionId) {
        try {
            $query = 'SELECT * FROM academic_questions WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $questionId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Update an academic question
    public function updateQuestion($questionId, $indexNo, $regNo, $fullName, $faculty, $telephone, $email, $question) {
        try {
            $query = 'UPDATE academic_questions 
                      SET index_no = :index_no, reg_no = :reg_no, full_name = :full_name, faculty = :faculty, 
                          telephone = :telephone, email = :email, question = :question
                      WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $questionId);
            $stmt->bindParam(':index_no', $indexNo);
            $stmt->bindParam(':reg_no', $regNo);
            $stmt->bindParam(':full_name', $fullName);
            $stmt->bindParam(':faculty', $faculty);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':question', $question);

            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Delete an academic question
    public function deleteQuestion($questionId) {
        try {
            $query = 'DELETE FROM academic_questions WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $questionId);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Update the status of a question (e.g., "pending" to "resolved")
    public function updateQuestionStatus($questionId, $status) {
        try {
            $query = 'UPDATE academic_questions SET status = :status WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $questionId);

            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }
    public function getPendingQuestions() {
    try {
        $query = 'SELECT * FROM academic_questions WHERE status = "pending" ORDER BY created_at DESC';
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $this->logError($e->getMessage());
        return [];
    }
}

    // Log errors
    private function logError($errorMessage) {
        $logFile = 'error_log.txt'; // Path to your error log file
        error_log($errorMessage, 3, $logFile);
    }
}
?>