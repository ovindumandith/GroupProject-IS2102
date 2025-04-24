<?php
// File: models/AdminProfileModel.php
require_once '../../config/config.php';

class AdminProfileModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    /**
     * Get admin profile information by user ID
     */
    public function getAdminProfileById($userId) {
        try {
            $query = "SELECT user_id, username, email, phone, created_at 
                      FROM users 
                      WHERE user_id = :user_id AND role = 'admin'";
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
     * Update admin profile information
     */
    public function updateAdminProfile($userId, $username, $email, $phone) {
        try {
            $query = "UPDATE users 
                      SET username = :username, email = :email, phone = :phone 
                      WHERE user_id = :user_id AND role = 'admin'";
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
     * Update admin password
     */
    public function updateAdminPassword($userId, $newPassword) {
        try {
            // In a real application, you should hash the password
            // $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $query = "UPDATE users 
                      SET password = :password 
                      WHERE user_id = :user_id AND role = 'admin'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Verify admin password
     */
    public function verifyPassword($userId, $password) {
        try {
            $query = "SELECT password FROM users WHERE user_id = :user_id AND role = 'admin'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $storedPassword = $stmt->fetchColumn();
            
            // Verify the password - adjust this if you use a different method
            return ($storedPassword === $password);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Get system statistics for admin
     */
    public function getSystemStats() {
        try {
            // Get user count by role
            $userQuery = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
            $userStmt = $this->db->prepare($userQuery);
            $userStmt->execute();
            $userStats = $userStmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Get appointment statistics
            $apptQuery = "SELECT status, COUNT(*) as count FROM appointments GROUP BY status";
            $apptStmt = $this->db->prepare($apptQuery);
            $apptStmt->execute();
            $apptStats = $apptStmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            // Get total academic questions
            $aqQuery = "SELECT COUNT(*) FROM academic_questions";
            $aqStmt = $this->db->prepare($aqQuery);
            $aqStmt->execute();
            $aqCount = $aqStmt->fetchColumn();
            
            // Return combined stats
            return [
                'user_stats' => $userStats,
                'appointment_stats' => $apptStats,
                'academic_questions_count' => $aqCount
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
?>