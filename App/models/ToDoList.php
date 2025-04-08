<?php
require_once '../../config/config.php';

class ToDoList
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }
    public function getAllTasks()
    {
        try {
            $query = "SELECT * FROM to_do_lists WHERE is_completed = 0 ORDER BY date, time";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    public function getTasksByDate($date)
    {
        $stmt = $this->db->prepare("SELECT * FROM to_do_lists WHERE date = :date");
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveTask($title, $date, $time)
    {
        try {
            $query = 'INSERT INTO to_do_lists (title,date,time) 
                      VALUES (:title,:date, :time)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':time', $time);

            if ($stmt->execute()) {

                return true;
            } else {
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    public function deleteTask($id)
    {
        $stmt = $this->db->prepare("DELETE FROM to_do_lists WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getEventById($eventId)
    {
        $stmt = $this->db->prepare("SELECT * FROM to_do_lists WHERE id = :eventId");
        $stmt->bindParam(':eventId', $eventId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Assuming you're using PDO
    }
    public function updateTask($id, $title, $date, $time)
    {

        try {
            $query = "UPDATE to_do_lists SET title = ?, date = ?, time = ? WHERE id = ?";
    
            // Prepare the statement
            $stmt = $this->db->prepare($query);
        
            // Binding parameters with proper types
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->bindParam(2, $date, PDO::PARAM_STR);
            $stmt->bindParam(3, $time, PDO::PARAM_STR);
        
            // Bind the id as an integer, but only if $id is provided (i.e., not NULL)
            if ($id !== null) {
                $stmt->bindParam(4, $id, PDO::PARAM_INT);
            } else {
                // Handle the case where ID is NULL (if necessary)
                // For example, throw an exception or return false
                return false; // Returning false since the task ID is required
            }
        
            // Execute the query and return the result
            if ($stmt->execute()) {
                return true; // Update successful
            } else {
                return false; // Update failed
            }
        } catch (\Throwable $th) {
           throw $th;
        }
        // Prepare the SQL query
      
    }
    public function updateTaskStatus($id,$is_completed)
    {
        error_log('AFSDSD'.$id,$is_completed);

        try {
            $query = "UPDATE to_do_lists SET is_completed = ? WHERE id = ?";
    
            // Prepare the statement
            $stmt = $this->db->prepare($query);
        
            // Binding parameters with proper types
            $stmt->bindParam(1, $is_completed, PDO::PARAM_STR);
          
            // Bind the id as an integer, but only if $id is provided (i.e., not NULL)
            if ($id !== null) {
                $stmt->bindParam(2, $id, PDO::PARAM_INT);
            } else {
                // Handle the case where ID is NULL (if necessary)
                // For example, throw an exception or return false
                return false; // Returning false since the task ID is required
            }
        
            // Execute the query and return the result
            if ($stmt->execute()) {
                return true; // Update successful
            } else {
                return false; // Update failed
            }
        } catch (\Throwable $th) {
           throw $th;
        }
        // Prepare the SQL query
      
    }
    public function getTaskBySearch($searchQuery)
    {
        // Sanitize input (for security)
        $searchQuery = "%" . $searchQuery . "%"; // Use wildcards for partial matching

        // SQL query to fetch events based on title or description
        $query = "SELECT * FROM to_do_lists WHERE title LIKE :searchQuery OR description LIKE :searchQuery";

        // Prepare the statement
        $stmt = $this->db->prepare($query);

        // Bind the search query
        $stmt->bindParam(':searchQuery', $searchQuery, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Fetch all matching events
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function checkTaskOverlap($date, $time, $title, $id = null)
    {
        // Assuming you have a database connection in $this->db
        $query = "SELECT * FROM to_do_lists WHERE title = ? AND date = ? AND time = ?";

        // If we're updating, exclude the current task from the check
        if ($id) {
            $query .= " AND id != ?";
        }

        // Prepare the query
        $stmt = $this->db->prepare($query);

        // Binding parameters with proper types
        $stmt->bindParam(1, $title, PDO::PARAM_STR);
        $stmt->bindParam(2, $date, PDO::PARAM_STR);
        $stmt->bindParam(3, $time, PDO::PARAM_STR);

        // If updating, bind the taskId to the last parameter
        if ($id) {
            $stmt->bindParam(4, $id, PDO::PARAM_INT); // taskId should be the last one

        }

        $stmt->execute();

        // Check if any rows were returned, meaning the event already exists
        return $stmt->rowCount() > 0;
    }
}
