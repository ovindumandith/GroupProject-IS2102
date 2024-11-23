<?php
require_once '../../config/config.php';

class CounselorModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    // Fetch all counselors
    public function getAllCounselors() {
        try {
            $query = 'SELECT * FROM counselors';
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return [];
        }
    }

    // Fetch a single counselor by ID
public function getCounselorById($id) {
    try {
        $query = 'SELECT * FROM counselors WHERE id = :id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $counselor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($counselor) {
            return $counselor;
        } else {
            return null; // If no counselor is found
        }
    } catch (PDOException $e) {
        $this->logError($e->getMessage());
        return null;
    }
}
// Fetch reviews for a specific counselor by ID
public function getReviewsByCounselorId($counselorId) {
    try {
        $query = 'SELECT r.id, r.user_id, r.rating, r.review_text, r.created_at, u.username AS reviewer_name 
                  FROM reviews r
                  JOIN users u ON r.user_id = u.user_id
                  WHERE r.counselor_id = :counselor_id
                  ORDER BY r.created_at DESC';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':counselor_id', $counselorId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $this->logError($e->getMessage());
        return [];
    }
}
public function addReview($counselorId, $userId, $rating, $reviewText) {
    try {
        $query = 'INSERT INTO reviews (counselor_id, user_id, rating, review_text, created_at) 
                  VALUES (:counselor_id, :user_id, :rating, :review_text, NOW())';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':counselor_id', $counselorId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':review_text', $reviewText, PDO::PARAM_STR);
        return $stmt->execute();
    } catch (PDOException $e) {
        $this->logError($e->getMessage());
        return false;
    }
}




    private function logError($errorMessage) {
        $logFile = 'error_log.txt';
        error_log($errorMessage, 3, $logFile);
    }
}
?>
