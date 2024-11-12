<?php
require_once '../../config/config.php';

class User {
    private $db;

    public function __construct() {
        // Initialize and connect to the database
        $this->db = new Database();
        $this->db = $this->db->connect();  // Assuming the connect method returns a PDO instance
    }

    // Validate user login credentials
    public function validateLogin($username, $password) {
        try {
            $query = 'SELECT * FROM users WHERE username = :username';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Test comparison (replace with password_verify in production)
            if ($user && $password == $user['password']) {  // Compare raw passwords for testing
                return $user;  // Return user data if successful
            } else {
                return false;   // Return false if validation fails
            }

        } catch (PDOException $e) {
            $this->logError($e->getMessage());
            return false;
        }
    }
    public function registerUser($username, $email, $phone, $year, $password) {
    try {
        // Check if the username or email already exists
        $query = 'SELECT * FROM users WHERE username = :username OR email = :email';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            return false;  // Username or email already exists
        }

        // Insert the new user
        $query = 'INSERT INTO users (username, email, phone, year, password, role, created_at) 
                  VALUES (:username, :email, :phone, :year, :password, "student", NOW())';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':password', $password);  // Plaintext password (testing)

        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}


    private function logError($errorMessage) {
        $logFile = 'error_log.txt'; // Path to your error log file
        error_log($errorMessage, 3, $logFile);
    }
}
?>
