<?php
// require_once 'C:\xampp\htdocs\GroupProject-IS2102\config\config.php';
require_once 'E:\xampp new\htdocs\GroupProject-IS2102\config\config.php';

class ForwardedQuestionModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    // Forward a question to lecturers by category
    public function forwardQuestionToLecturers($questionId, $category, $forwardedBy) {
        try {
            // Get all lecturers with the matching category
            $query = "SELECT l.user_id FROM lecturers l 
                      JOIN users u ON l.user_id = u.user_id 
                      WHERE u.role = 'lecturer' AND l.category = :category";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
            $stmt->execute();
            $lecturers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($lecturers)) {
                return false; // No lecturers found with this category
            }
            
            // Forward the question to each lecturer
            $success = true;
            foreach ($lecturers as $lecturer) {
                $insertQuery = "INSERT INTO forwarded_questions (question_id, lecturer_id, forwarded_by) 
                               VALUES (:question_id, :lecturer_id, :forwarded_by)";
                $insertStmt = $this->db->prepare($insertQuery);
                $insertStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
                $insertStmt->bindParam(':lecturer_id', $lecturer['user_id'], PDO::PARAM_INT);
                $insertStmt->bindParam(':forwarded_by', $forwardedBy, PDO::PARAM_INT);
                
                if (!$insertStmt->execute()) {
                    $success = false;
                }
            }
            
            // Update the question status if forwarded successfully
            if ($success) {
                $updateQuery = "UPDATE academic_questions SET status = 'Forwarded' WHERE id = :question_id";
                $updateStmt = $this->db->prepare($updateQuery);
                $updateStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
                $updateStmt->execute();
            }
            
            return $success;
        } catch (PDOException $e) {
            // Log error
            error_log($e->getMessage());
            return false;
        }
    }
    
    // Get all forwarded questions for a specific lecturer
    public function getForwardedQuestionsForLecturer($lecturerId) {
        try {
            $query = "SELECT fq.*, aq.question, aq.full_name AS student_name, aq.category, aq.created_at AS question_date,
                      u.username AS forwarded_by_name
                      FROM forwarded_questions fq
                      JOIN academic_questions aq ON fq.question_id = aq.id
                      JOIN users u ON fq.forwarded_by = u.user_id
                      WHERE fq.lecturer_id = :lecturer_id
                      ORDER BY fq.forwarded_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lecturer_id', $lecturerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error
            error_log($e->getMessage());
            return false;
        }
    }
    
    // Mark a forwarded question as read
    public function markAsRead($forwardedQuestionId, $lecturerId) {
        try {
            $query = "UPDATE forwarded_questions 
                      SET status = 'Read', read_at = CURRENT_TIMESTAMP 
                      WHERE id = :id AND lecturer_id = :lecturer_id AND status = 'Unread'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $forwardedQuestionId, PDO::PARAM_INT);
            $stmt->bindParam(':lecturer_id', $lecturerId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error
            error_log($e->getMessage());
            return false;
        }
    }
    
    // Mark a forwarded question as responded
    public function markAsResponded($forwardedQuestionId, $lecturerId) {
        try {
            $query = "UPDATE forwarded_questions 
                      SET status = 'Responded', responded_at = CURRENT_TIMESTAMP 
                      WHERE id = :id AND lecturer_id = :lecturer_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $forwardedQuestionId, PDO::PARAM_INT);
            $stmt->bindParam(':lecturer_id', $lecturerId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log error
            error_log($e->getMessage());
            return false;
        }
    }
    
    // Get unread count for a lecturer
    public function getUnreadCount($lecturerId) {
        try {
            $query = "SELECT COUNT(*) FROM forwarded_questions 
                      WHERE lecturer_id = :lecturer_id AND status = 'Unread'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lecturer_id', $lecturerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            // Log error
            error_log($e->getMessage());
            return 0;
        }
    }
    
    // Get forwarded question by ID
    public function getForwardedQuestionById($forwardedId, $lecturerId) {
        try {
            $query = "SELECT fq.*, aq.question, aq.full_name AS student_name, aq.category, 
                      aq.created_at AS question_date, u.username AS forwarded_by_name
                      FROM forwarded_questions fq
                      JOIN academic_questions aq ON fq.question_id = aq.id
                      JOIN users u ON fq.forwarded_by = u.user_id
                      WHERE fq.id = :id AND fq.lecturer_id = :lecturer_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $forwardedId, PDO::PARAM_INT);
            $stmt->bindParam(':lecturer_id', $lecturerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
?>