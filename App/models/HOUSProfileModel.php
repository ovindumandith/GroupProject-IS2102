// File: models/HOUSProfileModel.php
<?php
require_once '../../config/config.php';

class HOUSProfileModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    /**
     * Get HOUS profile information by user ID
     */
    public function getHOUSProfileById($userId) {
        try {
            $query = "SELECT user_id, username, email, phone, created_at 
                      FROM users 
                      WHERE user_id = :user_id AND role = 'hous'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Update HOUS profile information
     */
    public function updateHOUSProfile($userId, $username, $email, $phone) {
        try {
            $query = "UPDATE users 
                      SET username = :username, email = :email, phone = :phone 
                      WHERE user_id = :user_id AND role = 'hous'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Update HOUS password
     */
    public function updateHOUSPassword($userId, $newPassword) {
        try {
            // In a real application, you should hash the password
            // $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $query = "UPDATE users 
                      SET password = :password 
                      WHERE user_id = :user_id AND role = 'hous'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
?>