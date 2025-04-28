<?php
require_once '../../config/config.php';

class CounselorReviewsModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    /**
     * Get all reviews for a specific counselor
     * @param int $counselorId The counselor ID
     * @param int $limit Optional limit on number of reviews to return (0 for all)
     * @param int $offset Optional offset for pagination
     * @return array List of reviews with student details
     */
    public function getCounselorReviews($counselorId, $limit = 0, $offset = 0) {
        try {
            $query = "SELECT r.*, u.username as student_name 
                      FROM reviews r 
                      JOIN users u ON r.user_id = u.user_id
                      WHERE r.counselor_id = ?
                      ORDER BY r.created_at DESC";
                      
            // Add limit and offset if specified
            if ($limit > 0) {
                $query .= " LIMIT ?, ?";
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            
            // Bind limit and offset parameters if needed
            if ($limit > 0) {
                $stmt->bindParam(2, $offset, PDO::PARAM_INT);
                $stmt->bindParam(3, $limit, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Error fetching counselor reviews: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get total count of reviews for a specific counselor
     * @param int $counselorId The counselor ID
     * @return int Number of reviews
     */
    public function getTotalReviews($counselorId) {
        try {
            $query = "SELECT COUNT(*) FROM reviews WHERE counselor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->execute();
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->logError("Error counting reviews: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get average rating for a specific counselor
     * @param int $counselorId The counselor ID
     * @return float Average rating
     */
    public function getAverageRating($counselorId) {
        try {
            $query = "SELECT AVG(rating) FROM reviews WHERE counselor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->execute();
            $avgRating = $stmt->fetchColumn();
            return $avgRating ? round((float)$avgRating, 1) : 0.0;
        } catch (PDOException $e) {
            $this->logError("Error calculating average rating: " . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Get rating distribution for a specific counselor
     * @param int $counselorId The counselor ID
     * @return array Distribution of ratings (count per star rating)
     */
    public function getRatingDistribution($counselorId) {
        try {
            $query = "SELECT rating, COUNT(*) as count 
                      FROM reviews 
                      WHERE counselor_id = ? 
                      GROUP BY rating 
                      ORDER BY rating DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $counselorId, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Initialize distribution with all possible ratings
            $distribution = [
                5 => 0,
                4 => 0,
                3 => 0,
                2 => 0,
                1 => 0
            ];
            
            // Fill in actual counts
            foreach ($results as $row) {
                $distribution[$row['rating']] = (int)$row['count'];
            }
            
            return $distribution;
        } catch (PDOException $e) {
            $this->logError("Error fetching rating distribution: " . $e->getMessage());
            return [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        }
    }

    /**
     * Get all review statistics for a counselor
     * @param int $counselorId The counselor ID
     * @return array Review statistics
     */
    public function getReviewStatistics($counselorId) {
        return [
            'total_reviews' => $this->getTotalReviews($counselorId),
            'average_rating' => $this->getAverageRating($counselorId),
            'rating_distribution' => $this->getRatingDistribution($counselorId)
        ];
    }

    /**
     * Log errors to file
     * @param string $message Error message to log
     */
    private function logError($message) {
        $logDir = '../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/error_log.txt';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}