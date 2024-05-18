<?php
require_once __DIR__ . '/../mysql/Task.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskToDo = new Task($conn);
    $taskId = $_POST['taskId'];
    $newTitle = $_POST['title'];
    $newDescription = $_POST['description'];
    
    try {
        $taskToDo->updateTask($taskId, $newTitle, $newDescription);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $ex) {
        echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
    }
}
?>
