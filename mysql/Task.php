<?php
require_once __DIR__ . '/../config/config.php';

class Task
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;    
    }

    public function addTask($title, $description)
    {
        $query = 'INSERT INTO tasks (title, description) VALUES(:t, :d)';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':t', $title);
        $stmt->bindParam(':d', $description);

        try{$stmt->execute();}
        catch(PDOException $ex){throw new Exception('Failed to add task.. ' . $ex->getMessage());}
    }

    public function getTasks()
    {
        $query = "SELECT * FROM tasks";
        $stmt = $this->conn->prepare($query);
        try
        {
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $ex){throw new Exception('Failed to get task.. ' . $ex->getMessage());}
    }

    public function showTasks($id)
    {
        $query = 'SELECT * FROM tasks WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        try
        {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        catch(PDOException $ex){throw new Exception('Error fetching task: ' . $ex->getMessage());}
    }

    public function updateTask($id, $title, $description)
    {
        $query = 'UPDATE tasks SET title = :t, description = :d WHERE id = :id';
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':t', $title);
        $stmt->bindParam(':d', $description);
        $stmt->bindParam(':id', $id);
        
        try{ $stmt->execute(); }
        catch (PDOException $ex) {throw new Exception('Failed to update task: ' . $ex->getMessage());}
    }

    public function deleteTask($id)
    {
        $query = 'DELETE FROM tasks WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        try{ $stmt->execute(); }
        catch(PDOException $ex){throw new Exception('Failed to delete task: ' . $ex->getMessage());}
        
    }

    public function getTaskStatus($task_id)
{
    $query = 'SELECT task_status FROM completed_tasks WHERE task_id = :tid';
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':tid', $task_id);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return $result['task_status'];
    } else {
        return null;
    }
}

public function completeTask($task_id)
{
    $currentStatus = $this->getTaskStatus($task_id);

    if ($currentStatus === 'completed') {
        $query = 'UPDATE completed_tasks SET task_status = "not completed" WHERE task_id = :tid';
    } elseif ($currentStatus === 'not completed') {
        $query = 'UPDATE completed_tasks SET task_status = "completed" WHERE task_id = :tid';
    } else {
        $query = 'INSERT INTO completed_tasks (task_id, task_status) VALUES (:tid, "completed")';
    }

    $stmt  = $this->conn->prepare($query);
    $stmt->bindParam(':tid', $task_id);
    try {$stmt->execute();} 
    catch (PDOException $ex) {throw new Exception('Failed to update task status: ' . $ex->getMessage());}
}

 public function countTasks()
 {
    $query = 'SELECT COUNT(*) as count FROM tasks';
    $stmt = $this->conn->prepare($query);
    try{
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }  catch (PDOException $ex) {throw new Exception('Failed to count tasks: ' . $ex->getMessage());}
 }

 public function countCompletedTasks()
 {
    $query = 'SELECT COUNT(*) as count FROM completed_tasks WHERE  task_status = "completed"';
    $stmt = $this->conn->prepare($query);
    try{
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }  catch (PDOException $ex) {throw new Exception('Failed to count completed tasks ' . $ex->getMessage());}
 }

 public function countUncompletedTasks()
 {
    $query = 'SELECT COUNT(*) as count FROM completed_tasks WHERE  task_status = "not completed"';
    $stmt = $this->conn->prepare($query);
    try{
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }  catch (PDOException $ex) {throw new Exception('Failed to count uncompleted tasks ' . $ex->getMessage());}
 }
 

 

 
    
}    