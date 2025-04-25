<?php
require_once '../../config/config.php';

class ReviewModel {
    private $db;

    public function _construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

public function isUserReviewOwner($reviewId, $userId) {
    try {
        $query = 'SELECT id FROM reviews WHERE id = :review_id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':review_id', $reviewId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    } catch (PDOException $e) {
        $this->logError($e->getMessage());
        return false;
    }
}

public function updateReview($reviewId, $userId, $rating, $reviewText) {
    try {
        $query = 'UPDATE reviews SET rating = :rating, review_text = :review_text WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $reviewId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':review_text', $reviewText);
        return $stmt->execute();
    } catch (PDOException $e) {
        $this->logError($e->getMessage());
        return false;
    }
}

public function deleteReview($reviewId, $userId) {
    try {
        $query = 'DELETE FROM reviews WHERE id = :id AND user_id = :user_id';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $reviewId);
        $stmt->bindParam(':user_id', $userId);
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