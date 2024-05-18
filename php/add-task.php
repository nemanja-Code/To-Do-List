<?php
require_once __DIR__ . '/../mysql/Task.php';

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $taskToDo = new Task($conn);

    $title = $_POST['title'];
    $description = $_POST['description'];


    try
    {
        $taskToDo->addTask($title, $description);
        $id = $conn->lastInsertId();
        $getTask = $taskToDo->showTasks($id);
        $totalNumOfTasks = $taskToDo->countTasks();
        $totalNumOfCompletedTasks = $taskToDo->countCompletedTasks();
        $totalNumOfUncompletedTasks = $taskToDo->countUncompletedTasks();
        echo json_encode([
            'status' => 'success', 'getTask' => $getTask, 'totalNumOfTasks' => $totalNumOfTasks, 
            'totalNumOfCompletedTasks' => $totalNumOfCompletedTasks, 
            'totalNumOfUncompletedTasks'  =>  $totalNumOfUncompletedTasks
        ]);
    }
    
    catch(PDOException $ex)
    {
        echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
        
    }
}