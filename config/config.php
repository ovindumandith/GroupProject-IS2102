<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'stress_management'; // Ensure this matches the actual database name
    private $username = 'root'; // Default for local servers like WAMP/XAMPP
    private $password = '';     // Default is empty for local servers
    private $conn;

    public function connect() {
        $this->conn = null; // Initialize the connection as null
        try {
            // Attempt to create a PDO connection
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password);
            // Set PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle connection errors gracefully
            echo 'Connection Error: ' . $e->getMessage();
        }
        return $this->conn; // Return the connection (or null if it failed)
    }
}
?>
