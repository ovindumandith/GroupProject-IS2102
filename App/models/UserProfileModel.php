<?php
require_once '../../config/config.php';


/**
 * UserModel - Handles all database operations related to users
 */
class UserProfileModel {
    private $db;

    /**
     * Constructor - Initialize database connection
     */
    public function __construct() {
        
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    /**
     * Get user information by user ID
     * @param int $userId The user ID
     * @return array|null User data or null if not found
     */
    public function getUserById($userId) {
        try {
            $query = "SELECT user_id, username, password, email, phone, year, role FROM users WHERE user_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Error fetching user: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update user profile information
     * @param int $userId The user ID
     * @param array $data Associative array of user data to update
     * @return bool Success or failure
     */
    public function updateUser($userId, $data) {
        try {
            $query = "UPDATE users SET 
                      username = ?, 
                      password = ?, 
                      email = ?, 
                      phone = ?, 
                      year = ? 
                      WHERE user_id = ?";
                      
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $data['username']);
            $stmt->bindParam(2, $data['password']); // Plain text password as requested
            $stmt->bindParam(3, $data['email']);
            $stmt->bindParam(4, $data['phone']);
            $stmt->bindParam(5, $data['year']);
            $stmt->bindParam(6, $userId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError("Error updating user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if username already exists (for other users)
     * @param string $username The username to check
     * @param int $currentUserId The current user's ID (to exclude)
     * @return bool True if username exists for another user
     */
    public function usernameExists($username, $currentUserId) {
        try {
            $query = "SELECT COUNT(*) FROM users WHERE username = ? AND user_id != ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $currentUserId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            $this->logError("Error checking username: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if email already exists (for other users)
     * @param string $email The email to check
     * @param int $currentUserId The current user's ID (to exclude)
     * @return bool True if email exists for another user
     */
    public function emailExists($email, $currentUserId) {
        try {
            $query = "SELECT COUNT(*) FROM users WHERE email = ? AND user_id != ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $email);
            $stmt->bindParam(2, $currentUserId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            $this->logError("Error checking email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all appointments for a specific user
     * @param int $userId The user ID
     * @return array Array of appointment data
     */
    public function getUserAppointments($userId) {
        try {
            $query = "SELECT a.*, c.name as counselor_name 
                      FROM appointments a 
                      JOIN counselors c ON a.counselor_id = c.id 
                      WHERE a.student_id = ? 
                      ORDER BY a.appointment_date DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Error fetching appointments: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all academic questions/requests for a specific user
     * @param int $userId The user ID
     * @return array Array of academic questions data
     */
    public function getUserAcademicQuestions($userId) {
        try {
            $query = "SELECT * FROM academic_questions WHERE user_id = ? ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError("Error fetching academic questions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Log error to file
     * @param string $message Error message to log
     */
    private function logError($message) {
        // Create a log directory if it doesn't exist
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