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

    // Fetch the counselor profile when logged in
    public function getLoggedInCounselorProfile($counselorId) {
        try {
            $query = 'SELECT name, type, specialization, profile_image, description, email FROM counselors WHERE id = :id';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $counselorId, PDO::PARAM_INT);
            $stmt->execute();
            $counselor = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($counselor) {
                return $counselor;
            } else {
                return null; // If no counselor profile found
            }
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return null;
        }
    }

    // Update counselor profile
    public function updateCounselorProfile($id, $data) {
        $query = "UPDATE counselors SET 
                  name = :name, 
                  type = :type, 
                  specialization = :specialization, 
                  description = :description, 
                  email = :email, 
                  username = :username, 
                  updated_at = NOW() 
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':type', $data['type'], PDO::PARAM_STR);
        $stmt->bindParam(':specialization', $data['specialization'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);

        return $stmt->execute();
    }

    // Update counselor profile image
    public function updateProfileImage($id, $imagePath) {
        $query = "UPDATE counselors SET profile_image = :profile_image, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':profile_image', $imagePath, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Update counselor password
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE counselors SET password = :password, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Get counselor statistics
    public function getCounselorStats($id) {
        try {
            // Get total appointments
            $queryTotal = "SELECT COUNT(*) as total FROM appointments WHERE counselor_id = :id";
            $stmtTotal = $this->db->prepare($queryTotal);
            $stmtTotal->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtTotal->execute();
            $totalAppointments = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // Get completed/accepted appointments
            $queryCompleted = "SELECT COUNT(*) as completed FROM appointments WHERE counselor_id = :id AND status = 'Accepted'";
            $stmtCompleted = $this->db->prepare($queryCompleted);
            $stmtCompleted->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtCompleted->execute();
            $completedAppointments = $stmtCompleted->fetch(PDO::FETCH_ASSOC)['completed'] ?? 0;

            // Get average rating
            $queryRating = "SELECT AVG(rating) as avg_rating FROM reviews WHERE counselor_id = :id";
            $stmtRating = $this->db->prepare($queryRating);
            $stmtRating->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtRating->execute();
            $result = $stmtRating->fetch(PDO::FETCH_ASSOC);
            $averageRating = ($result && $result['avg_rating']) ? number_format($result['avg_rating'], 1) : '0.0';

            return [
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
                'average_rating' => $averageRating
            ];
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return [
                'total_appointments' => 0,
                'completed_appointments' => 0,
                'average_rating' => '0.0'
            ];
        }
    }

    private function logError($errorMessage) {
        $logFile = 'error_log.txt';
        error_log($errorMessage, 3, $logFile);
    }
}
?>
