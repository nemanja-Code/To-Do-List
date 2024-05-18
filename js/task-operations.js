function refreshChart(totalNumOfTasks, totalNumOfCompletedTasks, totalNumOfUncompletedTasks) {
    taskChart.data.datasets[0].data = [totalNumOfTasks, totalNumOfCompletedTasks, totalNumOfUncompletedTasks];
    taskChart.update();
}

//ADD TASK
const taskForm = document.getElementById('task-form');
const tableBody = document.querySelector('tbody');

taskForm.addEventListener('submit', e => {
     e.preventDefault();

     const form = new FormData(taskForm);

     fetch('php/add-task.php', {
         method: 'POST',
         body: form,

     }).then(response => {
        if(response.ok){
            return response.json();
        }else{throw new Error('Network was not ok.');}

     }).then(data => {
        if(data.status === 'success'){
            data.getTask.forEach(task => {
                addTaskToTable(task);
            });
            refreshChart(data.totalNumOfTasks, data.totalNumOfCompletedTasks, data.totalNumOfUncompletedTasks);
        }
     })
});


function addTaskToTable(task) {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${task.title}</td>
        <td>${task.description}</td>
        <td><input type="checkbox" class="completed-checkbox" name="completedCheckbox" data-check-id="${task.id}"></td>
        <td><button class="btn btn-primary update-task-btn" data-task-id="${task.id}">Update</button></td>
        <td><button class="btn btn-danger delete-task-btn" data-delete-id="${task.id}">Delete</button></td
       
    `;

    tableBody.appendChild(row);
}
//END OF ADD TASK


//UPDATE TASK
function updateTableRow(taskId, updatedTitle, updatedDescription) {
    const titleCell = document.querySelector(`td[data-task-title="${taskId}"]`);
    const descriptionCell = document.querySelector(`td[data-task-description="${taskId}"]`);
    
    if (titleCell && descriptionCell) {
        titleCell.innerText = updatedTitle;
        descriptionCell.innerText = updatedDescription;
    } else {
        console.error('Failed to find table cells for task ID:', taskId);
    }
}
const updateButtons = document.querySelectorAll('.update-task-btn');
updateButtons.forEach(button => {
    button.addEventListener('click', function() {
        const taskId = button.dataset.taskId;
        const taskRow = button.parentElement.parentElement;
        const title = taskRow.cells[0].innerText;
        const description = taskRow.cells[1].innerText;

        document.getElementById('taskId').value = taskId;
        document.getElementById('updateTitle').value = title;
        document.getElementById('updateDescription').value = description;

        const modal = new bootstrap.Modal(document.getElementById('updateTaskModal'));
        modal.show();
    });
});


const updateForm = document.getElementById('updateTaskForm');
updateForm.addEventListener('submit', function(event) {
    event.preventDefault();

    const form = new FormData(updateForm);

    fetch('php/update-task.php', {
        method: 'POST',
        body: form
    }).then(response => {
        if (response.ok) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('updateTaskModal'));
            modal.hide();
            const updatedTitle = document.getElementById('updateTitle').value;
            const updatedDescription = document.getElementById('updateDescription').value;
            const taskId = document.getElementById('taskId').value;
            updateTableRow(taskId, updatedTitle, updatedDescription);
        } else {
            throw new Error('Network response was not ok');
        }
    }).catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
});
//END OF UPDATE TASK


//DELETE TASK

const deleteButtons = document.querySelectorAll('.delete-task-btn');
deleteButtons.forEach(deleteButton => {
    deleteButton.addEventListener('click', () => {
        const taskId = deleteButton.dataset.deleteId; 

        fetch(`php/delete-task.php?id=${taskId}`, { 
            method: 'DELETE'
        }).then(response => {
            if(response.ok) {
                return response.json();
            } else {
                throw new Error('Network response was not ok.');
            }
        }).then(data => {
            if(data.status === 'success') {
                const tableRow = deleteButton.closest('tr');
                tableRow.remove();
                refreshChart(data.totalNumOfTasks, data.totalNumOfCompletedTasks, data.totalNumOfUncompletedTasks);
            }
        }).catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    });
});

//END OF DELETE TASK

//COMPLETE TASK
const forms = document.querySelectorAll('.complete-task-form');
forms.forEach(form => {
    const checkbox = form.querySelector('.completed-checkbox');
    const taskId = form.querySelector('.check-task-id').value;
    const isChecked = getCookie(`task_${taskId}_completed`);

    if (isChecked === 'true') {
        checkbox.checked = true;
    } else {
        checkbox.checked = false; 
    }
    checkbox.addEventListener('change', () => {
        completeTask(form, checkbox, taskId);
    });
});

function completeTask(form, checkbox, taskId){
    const checkTaskId = form.querySelector('.check-task-id').value;
    const taskStatus = form.querySelector('.task-status');

    fetch('php/updated-completed.php', {
        method: 'POST',
        body: new FormData(form) 
    }).then(response => {
        if(response.ok){
            console.log('Task completed successfully');
            return response.json();
        } else {
            throw new Error('Failed to complete task');
        }
    }).then(data => {
        if(data.status === 'success'){
            taskStatus.innerText = data.newStatus;
            if (data.newStatus === 'completed'){
                checkbox.checked = true; 
                setCookie(`task_${taskId}_completed`, 'true', 7);
                refreshChart(data.totalNumOfTasks, data.totalNumOfCompletedTasks, data.totalNumOfUncompletedTasks);
            }else if(data.newStatus === 'not completed'){
                checkbox.checked = false;
                setCookie(`task_${taskId}_completed`, 'false', 7);
                refreshChart(data.totalNumOfTasks, data.totalNumOfCompletedTasks, data.totalNumOfUncompletedTasks);
            }
           
          
        }
    }).catch(error => {
        console.error('Error:', error);
    });
}



function setCookie(name, value, days)
{
    const expires = new Date();
    expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
}

function getCookie(name)
{
    const cookies = document.cookie.split(';');

    for(let i = 0; i < cookies.length; i++){
        const cookie = cookies[i].trim();

        const cookieParts = cookie.split('=');

        if(cookieParts[0] === name){
            return cookieParts[1];
        }
    }
    return null;
}
//END OF COMPLETE TASK


//PIE CHART
const totalNumOfTasks = document.getElementById('totalTask').value;
const totalNumOfCompletedTasks = document.getElementById('totalCompleted').value;
const totalNumOfUncompletedTasks = document.getElementById('totalUncompleted').value;

const ctx = document.getElementById('taskChart').getContext('2d');
const taskChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Tasks', 'Completed', 'Not completed'],
        datasets: [{
            label: 'Total', 
            data: [totalNumOfTasks,totalNumOfCompletedTasks, totalNumOfUncompletedTasks],
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                display: false
            }
        },
       
    }
});


// END OF PIE CHART