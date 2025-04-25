<?php
require_once '../../config/config.php';

class TimeTrackingManagementModel
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
            $query = "SELECT * FROM goals   ORDER BY created_at";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    public function getAllWeeklyTask()
    {
        try {
            $query = "SELECT * FROM to_do_lists
                      WHERE YEARWEEK(date, 1) = YEARWEEK(NOW(), 1) 
                      ORDER BY date";

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

    public function saveTaskManagement($taskName, $description, $timeGoal, $timeUnit, $timeSpent, $completed, $createdAt)
    {
        try {
            $query = 'INSERT INTO goals (task_name, description, time_goal, time_unit, time_spent, completed, created_at) 
          VALUES (:task_name, :description, :time_goal, :time_unit, :time_spent, :completed, :created_at)';

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':task_name', $taskName);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':time_goal', $timeGoal, PDO::PARAM_INT);
            $stmt->bindParam(':time_unit', $timeUnit);
            $stmt->bindParam(':time_spent', $timeSpent, PDO::PARAM_INT);
            $stmt->bindParam(':completed', $completed, PDO::PARAM_BOOL);
            $stmt->bindParam(':created_at', date('Y-m-d H:i:s'));


            if ($stmt->execute()) {
                return true;
            } else {
                print_r($stmt->errorInfo()); // Debugging
                return false;
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }

    public function saveTaskHistories($action, $changeBy, $changeAt)
    {
        try {
            $query = 'INSERT INTO to_do_list_histories (action, change_by, change_at) 
                      VALUES (:action,:changeBy,:changeAt)';
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':action', $action);
            $stmt->bindParam(':changeBy', $changeBy);
            $stmt->bindParam(':changeAt', $changeAt);

            if ($stmt->execute()) {
                return true;
            } else {
                print_r($stmt->errorInfo()); // Show SQL error details
                return false;
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    public function deleteTask($id)
    {
        $stmt = $this->db->prepare("DELETE FROM goals WHERE id = :id");
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
    public function updateTask($id, $title, $date, $time, $description)
    {

        try {
            $query = "UPDATE to_do_lists SET title = ?, date = ?, time = ?,description= ? WHERE id = ?";

            // Prepare the statement
            $stmt = $this->db->prepare($query);

            // Binding parameters with proper types
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->bindParam(2, $date, PDO::PARAM_STR);
            $stmt->bindParam(3, $time, PDO::PARAM_STR);
            $stmt->bindParam(4, $description, PDO::PARAM_STR);

            // Bind the id as an integer, but only if $id is provided (i.e., not NULL)
            if ($id !== null) {
                $stmt->bindParam(5, $id, PDO::PARAM_INT);
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
    public function updateTaskStatus($id, $status)
    {
        try {
            $query = "UPDATE goals SET completed = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(1, $status, PDO::PARAM_STR);

            if ($id !== null) {
                $stmt->bindParam(2, $id, PDO::PARAM_INT); // Corrected index
            } else {
                return false;
            }

            return $stmt->execute(); // true if successful, false if not
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function updateTimeSpent(int $id): bool
{
    try {
        $sql  = "UPDATE goals
                   SET time_spent = time_spent + 1
                 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("DB error in updateTimeSpent: " . $e->getMessage());
        return false;
    }
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
        // Base query
        $query = "SELECT COUNT(*) FROM to_do_lists WHERE title = :title AND date = :date AND time = :time";

        // Exclude the current task ID if updating
        if ($id !== null) {
            $query .= " AND id != :id";
        }

        // Prepare the query
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":date", $date, PDO::PARAM_STR);
        $stmt->bindParam(":time", $time, PDO::PARAM_STR);

        if ($id !== null) {
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        }

        $stmt->execute();

        // Fetch count to check if any matching record exists
        return $stmt->fetchColumn() > 0;
    }
}