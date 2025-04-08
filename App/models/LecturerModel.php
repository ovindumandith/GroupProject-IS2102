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
}
?>