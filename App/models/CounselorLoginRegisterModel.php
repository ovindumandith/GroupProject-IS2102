<?php
require_once '../../config/config.php';

class CounselorLoginRegisterModel {
    private $db;

    public function __construct() {
        // Initialize and connect to the database
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    // Validate counselor login credentials by username
    public function validateLogin($username, $password) {
        try {
            $query = 'SELECT * FROM counselors WHERE username = :username';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $counselor = $stmt->fetch(PDO::FETCH_ASSOC);

            // Plaintext password comparison
            if ($counselor && $password == $counselor['password']) { 
                return $counselor; // Return counselor data if successful
            } else {
                return false; // Return false if validation fails
            }
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Check if a counselor exists by username (and email optional)
    public function isCounselorExist($username, $email = null) {
        try {
            // Check if username exists
            $query = 'SELECT * FROM counselors WHERE username = :username';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $counselor = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($counselor) {
                return true; // Username already exists
            }

            // Optionally check email if provided
            if ($email) {
                $query = 'SELECT * FROM counselors WHERE email = :email';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $counselor = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($counselor) {
                    return true; // Email already exists
                }
            }

            return false; // No counselor exists with that username or email
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Register a new counselor
    public function registerCounselor($name, $type, $specialization, $profileImage, $description, $email, $password, $username) {
        try {
            // Check if the username or email already exists
            if ($this->isCounselorExist($username, $email)) {
                return false; // Username or email already taken
            }

            // Insert the new counselor into the database
            $query = 'INSERT INTO counselors (name, type, specialization, profile_image, description, email, password, username) 
                      VALUES (:name, :type, :specialization, :profile_image, :description, :email, :password, :username)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':type', $type);
            $stmt->bindValue(':specialization', $specialization ?? null, PDO::PARAM_NULL);
            $stmt->bindValue(':profile_image', $profileImage ?? null, PDO::PARAM_NULL);
            $stmt->bindValue(':description', $description ?? null, PDO::PARAM_NULL);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password); // Store plaintext password (should be hashed in production)
            $stmt->bindParam(':username', $username);

            return $stmt->execute();
        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }

    // Log errors
    private function logError($errorMessage) {
        $logFile = 'error_log.txt'; // Path to your error log file
        error_log($errorMessage, 3, $logFile);
    }
}
?>
