<?php
require_once 'C:\xampp\htdocs\GroupProject-IS2102\config\config.php';

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
}
?>