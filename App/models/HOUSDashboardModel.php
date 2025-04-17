<!-- File: models/HOUSDashboardModel.php -->
<?php
require_once '../../config/config.php';

class HOUSDashboardModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }
    
    /**
     * Get count of academic questions by status
     */
    public function getQuestionCountsByStatus() {
        try {
            // Get basic counts from academic_questions
            $query = "SELECT 
                        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                        COUNT(*) as total_count
                      FROM academic_questions";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $basicCounts = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get count of unique questions from forwarded_questions
            $forwardedQuery = "SELECT COUNT(DISTINCT question_id) as forwarded_count 
                              FROM forwarded_questions";
            $forwardedStmt = $this->db->prepare($forwardedQuery);
            $forwardedStmt->execute();
            $forwardedCount = $forwardedStmt->fetchColumn();
            
            // Get count of unique questions from academic_question_response
            $repliedQuery = "SELECT COUNT(DISTINCT question_id) as replied_count 
                            FROM academic_question_response";
            $repliedStmt = $this->db->prepare($repliedQuery);
            $repliedStmt->execute();
            $repliedCount = $repliedStmt->fetchColumn();
            
            // Combine all counts
            return [
                'pending_count' => $basicCounts['pending_count'] ?? 0,
                'forwarded_count' => $forwardedCount ?? 0,
                'answered_count' => $repliedCount ?? 0,
                'total_count' => $basicCounts['total_count'] ?? 0
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [
                'pending_count' => 0,
                'forwarded_count' => 0,
                'answered_count' => 0,
                'total_count' => 0
            ];
        }
    }
    
    /**
     * Get count of lecturers
     */
    public function getLecturerCount() {
        try {
            $query = "SELECT COUNT(*) as lecturer_count FROM users WHERE role = 'lecturer'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get recent academic questions
     */
    public function getRecentQuestions($limit = 5) {
        try {
            $query = "SELECT 
                        aq.id, 
                        aq.full_name as student_name, 
                        aq.category, 
                        aq.question, 
                        aq.status, 
                        aq.created_at
                      FROM academic_questions aq
                      ORDER BY aq.created_at DESC
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
     * Get question counts by category
     */
    public function getQuestionCountsByCategory() {
        try {
            $query = "SELECT 
                        category, 
                        COUNT(*) as count
                      FROM academic_questions
                      WHERE category IS NOT NULL AND category != ''
                      GROUP BY category
                      ORDER BY count DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    
    /**
     * Get recent activity (forwarded questions and replies)
     */
    public function getRecentActivity($limit = 5) {
        try {
            // Get forwarded questions activity
            $forwardedQuery = "SELECT 
                                'forward' as activity_type,
                                fq.id as activity_id,
                                u1.username as actor_name,
                                u2.username as target_name,
                                aq.category,
                                fq.forwarded_at as activity_time
                              FROM forwarded_questions fq
                              JOIN users u1 ON fq.forwarded_by = u1.user_id
                              JOIN users u2 ON fq.lecturer_id = u2.user_id
                              JOIN academic_questions aq ON fq.question_id = aq.id
                              ORDER BY fq.forwarded_at DESC
                              LIMIT :limit1";
            $forwardedStmt = $this->db->prepare($forwardedQuery);
            $forwardedStmt->bindParam(':limit1', $limit, PDO::PARAM_INT);
            $forwardedStmt->execute();
            $forwardedActivity = $forwardedStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get replied questions activity
            $repliedQuery = "SELECT 
                              'reply' as activity_type,
                              qr.id as activity_id,
                              u.username as actor_name,
                              aq.full_name as target_name,
                              aq.category,
                              qr.created_at as activity_time
                            FROM question_replies qr
                            JOIN users u ON qr.user_id = u.user_id
                            JOIN academic_questions aq ON qr.question_id = aq.id
                            ORDER BY qr.created_at DESC
                            LIMIT :limit2";
            $repliedStmt = $this->db->prepare($repliedQuery);
            $repliedStmt->bindParam(':limit2', $limit, PDO::PARAM_INT);
            $repliedStmt->execute();
            $repliedActivity = $repliedStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Combine and sort activities
            $allActivities = array_merge($forwardedActivity, $repliedActivity);
            usort($allActivities, function($a, $b) {
                return strtotime($b['activity_time']) - strtotime($a['activity_time']);
            });
            
            // Return only the top activities
            return array_slice($allActivities, 0, $limit);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
?>
