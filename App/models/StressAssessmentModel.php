<?php
// require_once 'C:\xampp\htdocs\GroupProject-IS2102\config\config.php';
require_once 'E:\xampp new\htdocs\GroupProject-IS2102\config\config.php';

class StressAssessmentModel {
    private $db;

    public function __construct() {
        // Initialize and connect to the database
        $this->db = new Database();
        $this->db = $this->db->connect(); // Assuming the connect method returns a PDO instance
    }

    /**
     * Save user's stress assessment responses
     * 
     * @param int $userId User ID
     * @param array $responses Array of responses for all questions
     * @return bool Whether the operation was successful
     */
    public function saveStressAssessment($userId, $responses) {
        try {
            // Calculate section scores
            $section1_score = $responses['section1_q1'] + $responses['section1_q2'] + 
                              $responses['section1_q3'] + $responses['section1_q4'] + 
                              $responses['section1_q5'];
            
            $section2_score = $responses['section2_q1'] + $responses['section2_q2'] + 
                              $responses['section2_q3'] + $responses['section2_q4'] + 
                              $responses['section2_q5'];
            
            // Determine stress level
            $stress_level = $this->determineStressLevel($section1_score, $section2_score);
            
            $query = 'INSERT INTO stress_assessment 
                      (user_id, 
                       section1_q1, section1_q2, section1_q3, section1_q4, section1_q5,
                       section2_q1, section2_q2, section2_q3, section2_q4, section2_q5,
                       section1_score, section2_score, stress_level) 
                      VALUES 
                      (:user_id, 
                       :section1_q1, :section1_q2, :section1_q3, :section1_q4, :section1_q5,
                       :section2_q1, :section2_q2, :section2_q3, :section2_q4, :section2_q5,
                       :section1_score, :section2_score, :stress_level)';
            
            $stmt = $this->db->prepare($query);
            
            // Bind all parameters
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':section1_q1', $responses['section1_q1'], PDO::PARAM_INT);
            $stmt->bindParam(':section1_q2', $responses['section1_q2'], PDO::PARAM_INT);
            $stmt->bindParam(':section1_q3', $responses['section1_q3'], PDO::PARAM_INT);
            $stmt->bindParam(':section1_q4', $responses['section1_q4'], PDO::PARAM_INT);
            $stmt->bindParam(':section1_q5', $responses['section1_q5'], PDO::PARAM_INT);
            $stmt->bindParam(':section2_q1', $responses['section2_q1'], PDO::PARAM_INT);
            $stmt->bindParam(':section2_q2', $responses['section2_q2'], PDO::PARAM_INT);
            $stmt->bindParam(':section2_q3', $responses['section2_q3'], PDO::PARAM_INT);
            $stmt->bindParam(':section2_q4', $responses['section2_q4'], PDO::PARAM_INT);
            $stmt->bindParam(':section2_q5', $responses['section2_q5'], PDO::PARAM_INT);
            $stmt->bindParam(':section1_score', $section1_score, PDO::PARAM_INT);
            $stmt->bindParam(':section2_score', $section2_score, PDO::PARAM_INT);
            $stmt->bindParam(':stress_level', $stress_level, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    /**
     * Determine stress level based on section scores
     * 
     * @param int $section1_score Score for section 1
     * @param int $section2_score Score for section 2
     * @return string Stress level (High, Moderate, or Low)
     */
    private function determineStressLevel($section1_score, $section2_score) {
        $section1_level = '';
        $section2_level = '';
        
        // Determine section 1 stress level
        if ($section1_score > 15) {
            $section1_level = 'High';
        } elseif ($section1_score >= 10 && $section1_score <= 15) {
            $section1_level = 'Moderate';
        } else {
            $section1_level = 'Low';
        }
        
        // Determine section 2 stress level
        if ($section2_score < 5) {
            $section2_level = 'High';
        } elseif ($section2_score >= 5 && $section2_score <= 10) {
            $section2_level = 'Moderate';
        } else {
            $section2_level = 'Low';
        }
        
        // Return the higher stress level between the two sections
        if ($section1_level == 'High' || $section2_level == 'High') {
            return 'High';
        } elseif ($section1_level == 'Moderate' || $section2_level == 'Moderate') {
            return 'Moderate';
        } else {
            return 'Low';
        }
    }
    /**
 * Get all stress assessments for admin view
 * 
 * @return array All assessment records
 */
public function getAllStressAssessments() {
    try {
        $query = 'SELECT sa.*, u.name as student_name, u.user_id 
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
     * Retrieve all stress assessment records for a user
     * 
     * @param int $userId User ID
     * @return array Assessment records
     */
    public function getStressAssessmentRecords($userId) {
        try {
            $query = 'SELECT assessment_id, section1_score, section2_score, stress_level, assessment_date 
                      FROM stress_assessment 
                      WHERE user_id = :user_id 
                      ORDER BY assessment_date DESC';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
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
            $query = 'SELECT * FROM stress_assessment WHERE assessment_id = :assessment_id';
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
     * Get student's stress trend over time - modified to work for counselor view
     * 
     * @param int $userId Student ID 
     * @param int $limit Number of records to retrieve (default 10)
     * @return array Assessment records with stress levels
     */
    public function getStressTrend($userId, $limit = 10) {
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
     * Get the latest stress assessment for a specific student
     * 
     * @param int $userId Student ID
     * @return array|bool The latest assessment or false if none found
     */
    public function getLatestStressAssessment($userId) {
        try {
            $query = 'SELECT * FROM stress_assessment 
                      WHERE user_id = :user_id 
                      ORDER BY assessment_date DESC 
                      LIMIT 1';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    /**
     * Get relaxation techniques based on stress level
     * 
     * @param string $stressLevel The stress level (High, Moderate, or Low)
     * @return array Recommended techniques
     */
    public function getRecommendedTechniques($stressLevel) {
        $techniques = [
            'High' => [
                'Deep breathing exercises',
                'Progressive muscle relaxation',
                'Guided meditation',
                'Physical exercise',
                'Professional counseling'
            ],
            'Moderate' => [
                'Mindfulness practice',
                'Journaling',
                'Light physical activity',
                'Time management techniques',
                'Social support'
            ],
            'Low' => [
                'Maintain current practices',
                'Regular self-care',
                'Hobby engagement',
                'Positive affirmations',
                'Gratitude practice'
            ]
        ];
        
        return isset($techniques[$stressLevel]) ? $techniques[$stressLevel] : $techniques['Moderate'];
    }

    // Log database errors
    private function logError($errorMessage) {
        $logFile = '../../logs/error_log.txt'; // Ensure this path exists
        error_log($errorMessage, 3, $logFile);
    }
}
?>
