<?php
require_once __DIR__ . '/../mysql/Task.php';

if(isset($_GET['id'])){
    
    $id = $_GET['id'];
    $taskToDo = new Task($conn);

    try
    {
        $delete = $taskToDo->deleteTask($id);
        $totalNumOfTasks = $taskToDo->countTasks();
        $totalNumOfCompletedTasks = $taskToDo->countCompletedTasks();
        $totalNumOfUncompletedTasks = $taskToDo->countUncompletedTasks();
        echo json_encode([
            'status' => 'success',
            'totalNumOfTasks' => $totalNumOfTasks, 
            'totalNumOfCompletedTasks' => $totalNumOfCompletedTasks, 
            'totalNumOfUncompletedTasks'  =>  $totalNumOfUncompletedTasks
        ]);
    }
    catch(PDOException $ex){echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);}
}
else{ echo json_encode(['status' => 'error', 'message' => 'Task ID not provided']);}