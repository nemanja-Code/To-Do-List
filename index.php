<?php
require_once __DIR__ . '/mysql/Task.php';
$taskToDo = new Task($conn);
$tasks = $taskToDo->getTasks();
$totalNumOfTasks = $taskToDo->countTasks();
$totalNumOfCompletedTasks = $taskToDo->countCompletedTasks();
$totalNumOfUncompletedTasks = $taskToDo->countUncompletedTasks();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <title>To Do List</title>
   
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">To-Do List</h1>
    
    <div class="row">
        <div class="col-lg-6">
            <form id="task-form" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Task</button>
            </form>
            
               
    <table class="table table-bordered mt-4">
        <thead class="text-center">
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Task completed</th>
                <th scope="col">Update Task</th>
                <th scope="col">Delete Task</th>
            </tr>
        </thead>
        <tbody class="text-center">
        <?php foreach($tasks as $task): ?>
           <tr>
            <td data-task-title="<?=$task['id'];?>"><?=$task['title'];?></td>
            <td data-task-description="<?=$task['id'];?>"><?=$task['description'];?></td>
            <td>
            <form class="complete-task-form" action="php/updated-completed.php" method="post">
            <input type="hidden" class="check-task-id" name="check-task-id" value="<?=$task['id'];?>">
            <span class="task-status"><?=$task_status = $taskToDo->getTaskStatus($task['id']);?></span>
            <input type="checkbox" class="completed-checkbox" name="completedCheckbox" data-check-id="<?=$task['id'];?>">
            </form>
            </td>
            <td><button class="btn btn-primary update-task-btn" data-task-id="<?=$task['id'];?>">Update</button></td>
            <td><button class="btn btn-danger delete-task-btn" data-delete-id="<?=$task['id']?>">Delete</button></td>
           </tr>
           <?php endforeach; ?>
        </tbody>
    </table>
        </div>
        <div class="col-lg-6">
            <canvas id="taskChart"></canvas>
        </div>
    </div>

    <input type="hidden" id="totalTask" name="totalTask" value="<?=$totalNumOfTasks?>">
    <input type="hidden" id="totalCompleted" name="totalCompleted" value="<?=$totalNumOfCompletedTasks?>">
    <input type="hidden" id="totalUncompleted" name="totalUncompleted" value="<?=$totalNumOfUncompletedTasks?>">

  
</div>



<div class="modal fade" id="updateTaskModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateTaskModalLabel">Update Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateTaskForm">
          <input type="hidden" id="taskId" name="taskId" >
          <div class="mb-3">
            <label for="updateTitle" class="form-label">Title</label>
            <input type="text" class="form-control" id="updateTitle" name="title">
          </div>
          <div class="mb-3">
            <label for="updateDescription" class="form-label">Description</label>
            <textarea class="form-control" id="updateDescription" name="description"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      
      </div>
    </div>
  </div>


</div>

</body>
<script src="js/task-operations.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>




