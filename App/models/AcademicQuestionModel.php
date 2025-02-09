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
            $query = 'INSERT INTO academic_questions 
                      (user_id, index_no, reg_no, full_name, faculty, telephone, email, question, status, created_at) 
                      VALUES 
                      (:user_id, :index_no, :reg_no, :full_name, :faculty, :telephone, :email, :question, "pending", NOW())';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':index_no', $indexNo, PDO::PARAM_STR);
            $stmt->bindParam(':reg_no', $regNo, PDO::PARAM_STR);
            $stmt->bindParam(':full_name', $fullName, PDO::PARAM_STR); ;
            $stmt->bindParam(':faculty', $faculty, PDO::PARAM_STR);
            $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':question', $question, PDO::PARAM_STR);
            return $stmt->execute();
       
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
            $query = 'SELECT id, index_no, reg_no, full_name, faculty, question, status, created_at FROM
            academic_questions WHERE user_id = :user_id ORDER BY created_at DESC';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
    // Fetch a question along with its responses
public function getQuestionWithResponses($questionId) {
    $sql = "SELECT 
                q.id AS question_id,
                q.question,
                q.status AS question_status,
                q.created_at AS question_created_at,
                q.updated_at AS question_updated_at,
                r.response_id AS response_id,
                r.response,
                r.admin_id,
                r.created_at AS response_created_at,
                r.updated_at AS response_updated_at
            FROM academic_questions q
            LEFT JOIN academic_question_response r ON q.id = r.question_id
            WHERE q.id = :question_id";

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return false;
    }
}
public function updateQuestionStatusByStudent($questionId, $status) {
    try {

        // SQL query to update the status
        $sql = "UPDATE academic_questions SET status = :status, updated_at = NOW() WHERE id = :question_id";
        $stmt = $this->db->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);

        // Execute query
        return $stmt->execute();
    } catch (PDOException $e) {
        // Handle database error
        error_log("Database Error: " . $e->getMessage());
        return false;
    }
}
public function updateQuestionModal($questionId, $updatedQuestion) {
        $query = 'UPDATE academic_questions 
                  SET question = :question, updated_at = NOW() 
                  WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':question', $updatedQuestion, PDO::PARAM_STR);
        $stmt->bindParam(':id', $questionId, PDO::PARAM_INT);
        return $stmt->execute();
}
    public function saveReply($questionId, $adminId, $response) {
        // Prepare the SQL query
        $query = "INSERT INTO academic_question_response (question_id, admin_id, response, created_at, updated_at) 
                  VALUES (:question_id, :user_id, :response, NOW(), NOW())";
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $adminId, PDO::PARAM_INT);
        $stmt->bindParam(':response', $response, PDO::PARAM_STR);

        // Execute the query
        return $stmt->execute();
    }


    // Log errors
    private function logError($errorMessage) {
        $logFile = 'error_log.txt'; // Path to your error log file
        error_log($errorMessage, 3, $logFile);
    }
}
?>
