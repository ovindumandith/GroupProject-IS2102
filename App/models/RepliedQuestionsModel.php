<?php
require_once 'C:\xampp\htdocs\GroupProject-IS2102\config\config.php';

class RepliedQuestionsModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    /**
     * Add a reply to a forwarded question
     * @param int $questionId - The academic question ID
     * @param int $forwardedId - The forwarded question ID
     * @param int $lecturerId - The lecturer who is replying
     * @param string $replyText - The reply text
     * @return bool - Success or failure
     */
    public function addReply($questionId, $forwardedId, $lecturerId, $replyText) {
        try {
            // Begin transaction
            $this->db->beginTransaction();
            
            // Insert reply into question_replies table
            $insertQuery = "INSERT INTO question_replies (question_id, user_id, reply_text) 
                           VALUES (:question_id, :user_id, :reply_text)";
            $insertStmt = $this->db->prepare($insertQuery);
            $insertStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $insertStmt->bindParam(':user_id', $lecturerId, PDO::PARAM_INT);
            $insertStmt->bindParam(':reply_text', $replyText, PDO::PARAM_STR);
            $insertStmt->execute();
            
            // Update forwarded_questions status to Responded
            $updateForwardedQuery = "UPDATE forwarded_questions 
                                    SET status = 'Responded', responded_at = CURRENT_TIMESTAMP 
                                    WHERE id = :forwarded_id AND lecturer_id = :lecturer_id";
            $updateForwardedStmt = $this->db->prepare($updateForwardedQuery);
            $updateForwardedStmt->bindParam(':forwarded_id', $forwardedId, PDO::PARAM_INT);
            $updateForwardedStmt->bindParam(':lecturer_id', $lecturerId, PDO::PARAM_INT);
            $updateForwardedStmt->execute();
            
            // Update academic_questions status to Answered
            $updateQuestionQuery = "UPDATE academic_questions 
                                   SET status = 'Answered' 
                                   WHERE id = :question_id";
            $updateQuestionStmt = $this->db->prepare($updateQuestionQuery);
            $updateQuestionStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $updateQuestionStmt->execute();
            
            // Commit transaction
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // Rollback transaction if error
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all replied questions for head of undergraduate studies
     * @return array - Array of replied questions
     */
    public function getAllRepliedQuestions() {
        try {
            $query = "SELECT qr.id as reply_id, qr.question_id, qr.user_id as responder_id, 
                      qr.reply_text, qr.created_at as reply_date,
                      aq.full_name as student_name, aq.email as student_email, 
                      aq.student_id, aq.category, aq.question, aq.created_at as question_date,
                      u.username as responder_name, u.role as responder_role
                      FROM question_replies qr
                      JOIN academic_questions aq ON qr.question_id = aq.id
                      JOIN users u ON qr.user_id = u.user_id
                      WHERE u.role = 'lecturer'
                      ORDER BY qr.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return array();
        }
    }
    
    /**
     * Get replied questions for a specific student
     * @param string $studentId - The student ID
     * @return array - Array of replied questions
     */
    public function getRepliedQuestionsForStudent($studentId) {
        try {
            $query = "SELECT qr.id as reply_id, qr.question_id, qr.user_id as responder_id, 
                      qr.reply_text, qr.created_at as reply_date,
                      aq.full_name as student_name, aq.email as student_email, 
                      aq.student_id, aq.category, aq.question, aq.created_at as question_date,
                      u.username as responder_name, u.role as responder_role
                      FROM question_replies qr
                      JOIN academic_questions aq ON qr.question_id = aq.id
                      JOIN users u ON qr.user_id = u.user_id
                      WHERE aq.student_id = :student_id
                      ORDER BY qr.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':student_id', $studentId, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return array();
        }
    }
    
    /**
     * Get all replied questions by a specific lecturer
     * @param int $lecturerId - The lecturer ID
     * @return array - Array of replied questions
     */
    public function getRepliedQuestionsByLecturer($lecturerId) {
        try {
            $query = "SELECT qr.id as reply_id, qr.question_id, qr.user_id as responder_id, 
                      qr.reply_text, qr.created_at as reply_date,
                      aq.full_name as student_name, aq.email as student_email, 
                      aq.student_id, aq.category, aq.question, aq.created_at as question_date
                      FROM question_replies qr
                      JOIN academic_questions aq ON qr.question_id = aq.id
                      WHERE qr.user_id = :lecturer_id
                      ORDER BY qr.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':lecturer_id', $lecturerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return array();
        }
    }
    
    /**
     * Get a specific replied question
     * @param int $replyId - The reply ID
     * @return array|bool - Reply data or false
     */
    public function getReplyById($replyId) {
        try {
            $query = "SELECT qr.id as reply_id, qr.question_id, qr.user_id as responder_id, 
                      qr.reply_text, qr.created_at as reply_date,
                      aq.full_name as student_name, aq.email as student_email, 
                      aq.student_id, aq.category, aq.question, aq.created_at as question_date,
                      u.username as responder_name, u.role as responder_role
                      FROM question_replies qr
                      JOIN academic_questions aq ON qr.question_id = aq.id
                      JOIN users u ON qr.user_id = u.user_id
                      WHERE qr.id = :reply_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':reply_id', $replyId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
?>