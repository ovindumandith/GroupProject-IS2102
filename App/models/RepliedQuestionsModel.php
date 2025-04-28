<?php
// require_once 'C:\xampp\htdocs\GroupProject-IS2102\config\config.php';
require_once 'E:\xampp new\htdocs\GroupProject-IS2102\config\config.php';

class RepliedQuestionsModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    /**
     * Get all replied questions (for head of undergraduate studies)
     */
    public function getAllRepliedQuestions() {
        try {
            $query = "SELECT qr.id as reply_id, qr.question_id, qr.user_id as responder_id, 
                      qr.reply_text, qr.created_at as reply_date,
                      aq.full_name as student_name, aq.email as student_email, 
                      aq.reg_no as student_id, aq.category, aq.question, aq.created_at as question_date,
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
     */
    public function getRepliedQuestionsForStudent($studentId) {
        try {
            $query = "SELECT qr.id as reply_id, qr.question_id, qr.user_id as responder_id, 
                      qr.reply_text, qr.created_at as reply_date,
                      aq.full_name as student_name, aq.email as student_email, 
                      aq.reg_no as student_id, aq.category, aq.question, aq.created_at as question_date,
                      u.username as responder_name, u.role as responder_role
                      FROM question_replies qr
                      JOIN academic_questions aq ON qr.question_id = aq.id
                      JOIN users u ON qr.user_id = u.user_id
                      WHERE aq.reg_no = :student_id
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
     */
    public function getRepliedQuestionsByLecturer($lecturerId) {
        try {
            $query = "SELECT qr.id as reply_id, qr.question_id, qr.user_id as responder_id, 
                      qr.reply_text, qr.created_at as reply_date,
                      aq.full_name as student_name, aq.email as student_email, 
                      aq.reg_no as student_id, aq.category, aq.question, aq.created_at as question_date
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
     */
    public function getReplyById($replyId) {
        try {
            $query = "SELECT qr.id as reply_id, qr.question_id, qr.user_id as responder_id, 
                      qr.reply_text, qr.created_at as reply_date,
                      aq.full_name as student_name, aq.email as student_email, 
                      aq.reg_no as student_id, aq.category, aq.question, aq.created_at as question_date,
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
     * Add debugging to help identify issues
     */
    public function debug() {
        try {
            // Check if the question_replies table has records
            $query = "SELECT COUNT(*) as count FROM question_replies";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Get a sample record to check column names
            $querySample = "SELECT * FROM question_replies LIMIT 1";
            $stmtSample = $this->db->prepare($querySample);
            $stmtSample->execute();
            $sample = $stmtSample->fetch(PDO::FETCH_ASSOC);

            return [
                'count' => $result['count'],
                'sample' => $sample,
                'tables' => $this->getTableNames(),
                'columns' => [
                    'question_replies' => $this->getColumnNames('question_replies'),
                    'academic_questions' => $this->getColumnNames('academic_questions'),
                    'users' => $this->getColumnNames('users')
                ]
            ];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get all table names in the database
     */
    private function getTableNames() {
        try {
            $query = "SHOW TABLES";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $tables = [];
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
            return $tables;
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get all column names for a specified table
     */
    private function getColumnNames($tableName) {
        try {
            $query = "SHOW COLUMNS FROM " . $tableName;
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $columns = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $columns[] = $row['Field'];
            }
            return $columns;
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Update a reply
     * @param int $replyId The ID of the reply to update
     * @param string $newReplyText The new reply text
     * @param int $userId The user ID who is updating (for verification)
     * @return bool Success or failure
     */
    public function updateReply($replyId, $newReplyText, $userId) {
        try {
            // Verify the reply belongs to this user before updating
            $verifyQuery = "SELECT id FROM question_replies 
                            WHERE id = :reply_id AND user_id = :user_id";
            $verifyStmt = $this->db->prepare($verifyQuery);
            $verifyStmt->bindParam(':reply_id', $replyId, PDO::PARAM_INT);
            $verifyStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $verifyStmt->execute();

            if ($verifyStmt->rowCount() === 0) {
                // Reply doesn't belong to this user
                return false;
            }

            // Update the reply
            $query = "UPDATE question_replies 
                      SET reply_text = :reply_text, updated_at = CURRENT_TIMESTAMP 
                      WHERE id = :reply_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':reply_id', $replyId, PDO::PARAM_INT);
            $stmt->bindParam(':reply_text', $newReplyText, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Delete a reply
     * @param int $replyId The ID of the reply to delete
     * @param int $userId The user ID who is deleting (for verification)
     * @return bool Success or failure
     */
    public function deleteReply($replyId, $userId) {
    try {
        // Begin transaction
        $this->db->beginTransaction();

        // Get question_id before deleting the reply
        $getQuestionIdQuery = "SELECT question_id FROM question_replies WHERE id = :reply_id AND user_id = :user_id";
        $getQuestionIdStmt = $this->db->prepare($getQuestionIdQuery);
        $getQuestionIdStmt->bindParam(':reply_id', $replyId, PDO::PARAM_INT);
        $getQuestionIdStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $getQuestionIdStmt->execute();
        
        if ($getQuestionIdStmt->rowCount() === 0) {
            // Reply doesn't exist or doesn't belong to this user
            $this->db->rollBack();
            return false;
        }
        
        $questionId = $getQuestionIdStmt->fetchColumn();
        
        // Delete the reply
        $deleteQuery = "DELETE FROM question_replies WHERE id = :reply_id";
        $deleteStmt = $this->db->prepare($deleteQuery);
        $deleteStmt->bindParam(':reply_id', $replyId, PDO::PARAM_INT);
        
        if (!$deleteStmt->execute()) {
            $this->db->rollBack();
            return false;
        }

        // Update forwarded question status
        $updateForwardedQuery = "UPDATE forwarded_questions 
                                SET status = 'Read', responded_at = NULL 
                                WHERE question_id = :question_id AND lecturer_id = :lecturer_id";
        $updateForwardedStmt = $this->db->prepare($updateForwardedQuery);
        $updateForwardedStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        $updateForwardedStmt->bindParam(':lecturer_id', $userId, PDO::PARAM_INT);
        $updateForwardedStmt->execute();
        
        // Check if there are any other replies to this question
        $checkRepliesQuery = "SELECT COUNT(*) FROM question_replies WHERE question_id = :question_id";
        $checkRepliesStmt = $this->db->prepare($checkRepliesQuery);
        $checkRepliesStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
        $checkRepliesStmt->execute();
        
        if ($checkRepliesStmt->fetchColumn() == 0) {
            // No other replies exist, update question status back to Forwarded
            $updateQuestionQuery = "UPDATE academic_questions 
                                   SET status = 'Forwarded' 
                                   WHERE id = :question_id";
            $updateQuestionStmt = $this->db->prepare($updateQuestionQuery);
            $updateQuestionStmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $updateQuestionStmt->execute();
        }
        
        $this->db->commit();
        return true;
    } catch (PDOException $e) {
        $this->db->rollBack();
        error_log("Delete reply error: " . $e->getMessage());
        return false;
    }
}
}
?>