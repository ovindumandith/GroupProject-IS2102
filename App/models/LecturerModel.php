<?php
require_once 'C:\xampp\htdocs\GroupProject-IS2102\config\config.php';

class LecturerModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

    // Get all lecturers
    public function getAllLecturers() {
        try {
            $query = "SELECT l.*, u.role FROM lecturers l 
                      JOIN users u ON l.user_id = u.user_id 
                      ORDER BY l.name";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Get lecturers by category
    public function getLecturersByCategory($category) {
        try {
            $query = "SELECT l.*, u.role FROM lecturers l 
                      JOIN users u ON l.user_id = u.user_id 
                      WHERE l.category = :category 
                      ORDER BY l.name";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Get lecturer by ID
    public function getLecturerById($id) {
        try {
            $query = "SELECT l.*, u.role FROM lecturers l 
                      JOIN users u ON l.user_id = u.user_id 
                      WHERE l.id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Get lecturer by user ID
    public function getLecturerByUserId($userId) {
        try {
            $query = "SELECT l.*, u.role FROM lecturers l 
                      JOIN users u ON l.user_id = u.user_id 
                      WHERE l.user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Add a new lecturer
    public function addLecturer($userData) {
        try {
            $query = "INSERT INTO lecturers (user_id, name, email, department, category, profile_image, bio) 
                      VALUES (:user_id, :name, :email, :department, :category, :profile_image, :bio)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userData['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $userData['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $userData['email'], PDO::PARAM_STR);
            $stmt->bindParam(':department', $userData['department'], PDO::PARAM_STR);
            $stmt->bindParam(':category', $userData['category'], PDO::PARAM_STR);
            $stmt->bindParam(':profile_image', $userData['profile_image'], PDO::PARAM_STR);
            $stmt->bindParam(':bio', $userData['bio'], PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Update a lecturer
    public function updateLecturer($id, $userData) {
        try {
            $query = "UPDATE lecturers 
                      SET name = :name, email = :email, department = :department, 
                          category = :category, profile_image = :profile_image, bio = :bio 
                      WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $userData['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $userData['email'], PDO::PARAM_STR);
            $stmt->bindParam(':department', $userData['department'], PDO::PARAM_STR);
            $stmt->bindParam(':category', $userData['category'], PDO::PARAM_STR);
            $stmt->bindParam(':profile_image', $userData['profile_image'], PDO::PARAM_STR);
            $stmt->bindParam(':bio', $userData['bio'], PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Delete a lecturer
    public function deleteLecturer($id) {
        try {
            $query = "DELETE FROM lecturers WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Get all available categories
    public function getAllCategories() {
        try {
            $query = "SELECT DISTINCT category FROM lecturers ORDER BY category";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Update a lecturer by user_id instead of id
     * @param int $userId The user ID of the lecturer
     * @param array $userData The data to update
     * @return bool Success or failure
     */
    public function updateLecturerByUserId($userId, $userData) {
        try {
            // First check if lecturer exists with this user_id
            $checkQuery = "SELECT id FROM lecturers WHERE user_id = :user_id";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                // Lecturer exists, update it
                $lecturerId = $checkStmt->fetchColumn();

                $query = "UPDATE lecturers 
                          SET name = :name, 
                              email = :email, 
                              department = :department, 
                              category = :category, 
                              bio = :bio
                          WHERE user_id = :user_id";
            } else {
                // Lecturer doesn't exist, insert new record
                $query = "INSERT INTO lecturers (user_id, name, email, department, category, bio) 
                          VALUES (:user_id, :name, :email, :department, :category, :bio)";
            }

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':name', $userData['name'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $userData['email'], PDO::PARAM_STR);
            $stmt->bindParam(':department', $userData['department'], PDO::PARAM_STR);
            $stmt->bindParam(':category', $userData['category'], PDO::PARAM_STR);
            $stmt->bindParam(':bio', $userData['bio'], PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user information in the users table
     * @param int $userId
     * @param string $email
     * @param string $phone
     * @return bool
     */
    public function updateUserInfo($userId, $email, $phone) {
        try {
            $query = "UPDATE users SET email = :email, phone = :phone WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user information from the users table
     * @param int $userId
     * @return array|false
     */
    public function getUserById($userId) {
        try {
            $query = "SELECT * FROM users WHERE user_id = :user_id";
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
     * Verify user password
     * @param int $userId
     * @param string $password
     * @return bool
     */
    public function verifyPassword($userId, $password) {
        try {
            $query = "SELECT password FROM users WHERE user_id = :user_id";
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
     * Update user password
     * @param int $userId
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword($userId, $newPassword) {
        try {
            $query = "UPDATE users SET password = :password WHERE user_id = :user_id";
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