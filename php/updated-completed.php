<?php
require_once __DIR__ . '/../mysql/Task.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['check-task-id'])) {
        $taskId = $_POST['check-task-id'];

        $taskToDo = new Task($conn);
        $taskToDo->completeTask($taskId);
        $newStatus = $taskToDo->getTaskStatus($taskId);
        $totalNumOfTasks = $taskToDo->countTasks();
        $totalNumOfCompletedTasks = $taskToDo->countCompletedTasks();
        $totalNumOfUncompletedTasks = $taskToDo->countUncompletedTasks();

        echo json_encode([
            'status' => 'success', 'newStatus' => $newStatus,
            'totalNumOfTasks' => $totalNumOfTasks, 
            'totalNumOfCompletedTasks' => $totalNumOfCompletedTasks, 
            'totalNumOfUncompletedTasks'  =>  $totalNumOfUncompletedTasks
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Task ID not provided']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
