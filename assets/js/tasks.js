// JavaScript for Task Management (Dashboard Page)

// Assumes main.js is loaded first and provides escapeHTML and showMessage
// If not, define them here or ensure they are globally available.
// function escapeHTML(str) { ... }
// function showMessage(elementId, message, type = 'info') { ... }

// Store loaded tasks globally within this script's scope to access for editing
let currentTasks = [];

document.addEventListener('DOMContentLoaded', () => {
    // Only run task-specific code if we are on the dashboard
    const taskListContainer = document.getElementById('task-list-container');
    const addTaskForm = document.getElementById('add-task-form'); // Get form reference

    if (taskListContainer) {
        console.log('Tasks JS Loaded');
        loadTasks(); // Initial load
        setupEventListeners(taskListContainer, addTaskForm); // Setup listeners
    } else {
        console.log('Task list container not found, Tasks JS not fully initialized.');
    }
});

/**
 * Fetches tasks from the API and initiates display.
 */
async function loadTasks() {
    console.log('Loading tasks...');
    const container = document.getElementById('task-list-container');
    if (!container) return;
    container.innerHTML = '<p>Loading tasks...</p>'; // Show loading state

    try {
        const response = await fetch('api/tasks/read.php'); // GET request by default
        if (!response.ok) {
            // Handle non-OK responses (like 401 Unauthorized)
            if (response.status === 401) {
                 console.error('Authentication error loading tasks.');
                 showMessage('task-list-container', 'Authentication error. Please login again.', 'error');
                 // Optionally redirect to login
                 // window.location.href = 'login.php';
                 return; // Stop further processing
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const result = await response.json();

        if (result.success) {
            currentTasks = result.tasks; // Store tasks globally
            displayTasks(currentTasks); // Pass the stored tasks
        } else {
            currentTasks = []; // Clear tasks on error
            console.error("API error loading tasks:", result.message);
            showMessage('task-list-container', `Failed to load tasks: ${result.message}`, 'error');
        }
    } catch (error) {
        console.error("Could not load tasks:", error);
        showMessage('task-list-container', `Error loading tasks: ${error.message}.`, 'error');
    }
}

/**
 * Displays tasks grouped by status in the UI.
 * @param {Array} tasks Array of task objects.
 */
function displayTasks(tasks) {
    const container = document.getElementById('task-list-container');
    if (!container) return;

    container.innerHTML = ''; // Clear loading message or previous tasks

    if (!tasks || tasks.length === 0) {
        container.innerHTML = '<p class="no-tasks-message">No tasks found. Add one using the form above!</p>';
        return;
    }

    // Group tasks (optional, can render all together initially)
    const pendingTasks = tasks.filter(task => task.status === 'pending');
    const completedTasks = tasks.filter(task => task.status === 'completed');

    // Create sections for pending and completed tasks
    const pendingSection = document.createElement('div');
    pendingSection.id = 'pending-tasks';
    pendingSection.innerHTML = '<h3>Pending</h3>';
    if (pendingTasks.length === 0) {
        pendingSection.innerHTML += '<p>No pending tasks.</p>';
    } else {
        pendingTasks.forEach(task => pendingSection.appendChild(createTaskElement(task)));
    }

    const completedSection = document.createElement('div');
    completedSection.id = 'completed-tasks';
    completedSection.innerHTML = '<h3>Completed</h3>';
     if (completedTasks.length === 0) {
        completedSection.innerHTML += '<p>No completed tasks.</p>';
    } else {
        completedTasks.forEach(task => completedSection.appendChild(createTaskElement(task)));
    }

    container.appendChild(pendingSection);
    container.appendChild(completedSection);

    // Apply animations if needed (e.g., using function from animations.js)
    // applyStaggeredAnimation('#pending-tasks .task-card', 'task-card-load-animation', 0.05);
    // applyStaggeredAnimation('#completed-tasks .task-card', 'task-card-load-animation', 0.05);
}

/**
 * Creates the HTML element for a single task.
 * @param {object} task The task object.
 * @returns {HTMLElement} The task card element.
 */
function createTaskElement(task) {
    const taskElement = document.createElement('div');
    // Add classes based on priority and status for styling
    taskElement.className = `task-card priority-${task.priority} status-${task.status}`;
    taskElement.dataset.taskId = task.id; // Store task ID

    // Use escapeHTML from main.js or define locally if needed
    const title = typeof escapeHTML === 'function' ? escapeHTML(task.title) : task.title;
    const description = typeof escapeHTML === 'function' ? escapeHTML(task.description || '') : (task.description || '');

    taskElement.innerHTML = `
        <div class="task-card-content">
            <h4 class="task-title">${title}</h4>
            ${description ? `<p class="task-description">${description}</p>` : ''}
            <div class="task-meta">
                <span>Priority: <span class="priority-label">${task.priority}</span></span>
                ${task.due_date ? `<span>Due: ${task.due_date}</span>` : ''}
            </div>
        </div>
        <div class="task-card-actions">
            <button class="edit-task-btn icon-btn" title="Edit Task" data-id="${task.id}"><i class="fas fa-pencil-alt"></i></button>
            <button class="delete-task-btn icon-btn" title="Delete Task" data-id="${task.id}"><i class="fas fa-trash-alt"></i></button>
            <input type="checkbox" class="complete-task-chk" title="Mark as ${task.status === 'completed' ? 'Pending' : 'Completed'}" data-id="${task.id}" ${task.status === 'completed' ? 'checked' : ''}>
        </div>
    `;
    return taskElement;
}


/**
 * Sets up event listeners for task interactions.
 * @param {HTMLElement} taskContainer The container holding the tasks.
 * @param {HTMLFormElement} addTaskForm The form for adding new tasks.
 */
function setupEventListeners(taskContainer, addTaskForm) {
    // Add Task Form Submission
    addTaskForm?.addEventListener('submit', handleAddTask);

    // Event delegation for edit, delete, complete buttons within the task list
    taskContainer?.addEventListener('click', (event) => {
        const target = event.target;
        const taskId = target.closest('.task-card')?.dataset.taskId || target.dataset.id; // Get task ID from button or checkbox

        if (!taskId) return; // Exit if click wasn't on an actionable item with an ID

        if (target.classList.contains('edit-task-btn') || target.closest('.edit-task-btn')) {
             openEditModal(taskId); // Call function to open and populate modal
        } else if (target.classList.contains('delete-task-btn') || target.closest('.delete-task-btn')) {
             handleDeleteTask(taskId);
        } else if (target.classList.contains('complete-task-chk')) {
             handleToggleComplete(taskId, target.checked);
        }
    });
}

/**
 * Handles the submission of the 'Add Task' form.
 */
async function handleAddTask(event) {
    event.preventDefault();
    const form = event.target;
    const titleInput = form.querySelector('#new-task-title');
    const descriptionInput = form.querySelector('#new-task-description');
    // Get priority and due date inputs if they exist
    // const priorityInput = form.querySelector('#new-task-priority');
    // const dueDateInput = form.querySelector('#new-task-due-date');

    const taskData = {
        title: titleInput.value.trim(),
        description: descriptionInput.value.trim(),
        // priority: priorityInput?.value || 'medium',
        // due_date: dueDateInput?.value || null
    };

    if (!taskData.title) {
        // Use showMessage from main.js if available
        if (typeof showMessage === 'function') {
            showMessage('add-task-message', 'Task title cannot be empty.', 'error'); // Assuming an element with id="add-task-message" exists
        } else {
            alert('Task title cannot be empty.');
        }
        return;
    }

    console.log("Adding task:", taskData);

    try {
        const response = await fetch('api/tasks/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(taskData)
        });

        const result = await response.json();

        if (result.success) {
            console.log("Task added successfully:", result);
            form.reset(); // Clear the form
            loadTasks(); // Reload the task list
             if (typeof showMessage === 'function') {
                 // Clear any previous error message
                 const msgElement = document.getElementById('add-task-message');
                 if(msgElement) msgElement.style.display = 'none';
             }
        } else {
            console.error("API error adding task:", result.message);
             if (typeof showMessage === 'function') {
                 showMessage('add-task-message', `Failed to add task: ${result.message}`, 'error');
             } else {
                 alert(`Failed to add task: ${result.message}`);
             }
        }
    } catch (error) {
        console.error("Could not add task:", error);
         if (typeof showMessage === 'function') {
             showMessage('add-task-message', `Error adding task: ${error.message}.`, 'error');
         } else {
             alert(`Error adding task: ${error.message}.`);
         }
    }
}

/**
 * Handles deleting a task.
 * @param {string} taskId The ID of the task to delete.
 */
async function handleDeleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) {
        return; // User cancelled
    }

    console.log("Deleting task:", taskId);

    try {
        const response = await fetch('api/tasks/delete.php', {
            method: 'POST', // Using POST as defined in the PHP script
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ task_id: taskId })
        });

        const result = await response.json();

        if (result.success) {
            console.log("Task deleted successfully");
            // Remove the task element from the DOM directly for faster feedback
            const taskElement = document.querySelector(`.task-card[data-task-id="${taskId}"]`);
            taskElement?.remove();
            // Optionally, reload the whole list: loadTasks();
        } else {
            console.error("API error deleting task:", result.message);
            alert(`Failed to delete task: ${result.message}`);
        }
    } catch (error) {
        console.error("Could not delete task:", error);
        alert(`Error deleting task: ${error.message}.`);
    }
}

/**
 * Handles toggling the completion status of a task.
 * @param {string} taskId The ID of the task to update.
 * @param {boolean} isComplete The new completion status (true if checked).
 */
async function handleToggleComplete(taskId, isComplete) {
    const newStatus = isComplete ? 'completed' : 'pending';
    console.log(`Toggling task ${taskId} to ${newStatus}`);

    const updateData = {
        task_id: taskId,
        status: newStatus
    };

    try {
        const response = await fetch('api/tasks/update.php', {
            method: 'POST', // Using POST as defined in the PHP script
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(updateData)
        });

        const result = await response.json();

        if (result.success) {
            console.log("Task status updated successfully");
            // Visually update the task card or reload the list
            loadTasks(); // Reloading is simpler for now to handle moving between sections
        } else {
            console.error("API error updating task status:", result.message);
            alert(`Failed to update task status: ${result.message}`);
            // Revert checkbox state on failure
            const checkbox = document.querySelector(`.task-card[data-task-id="${taskId}"] .complete-task-chk`);
            if (checkbox) checkbox.checked = !isComplete;
        }
    } catch (error) {
        console.error("Could not update task status:", error);
        alert(`Error updating task status: ${error.message}.`);
        // Revert checkbox state on failure
        const checkbox = document.querySelector(`.task-card[data-task-id="${taskId}"] .complete-task-chk`);
        if (checkbox) checkbox.checked = !isComplete;
    }

    // Add listener for the edit form submission
    const editTaskForm = document.getElementById('edit-task-form');
    editTaskForm?.addEventListener('submit', handleEditTaskSubmit);
}

// --- Edit Modal Functions ---

/**
 * Opens the edit modal and populates it with task data.
 * @param {string} taskId The ID of the task to edit.
 */
function openEditModal(taskId) {
    const modal = document.getElementById('edit-task-modal');
    const form = document.getElementById('edit-task-form');
    if (!modal || !form) {
        console.error("Edit modal or form not found!");
        return;
    }

    // Find the task data from the globally stored list
    const task = currentTasks.find(t => t.id == taskId); // Use == for potential type difference
    if (!task) {
        console.error("Task data not found for ID:", taskId);
        alert("Could not find task data to edit.");
        return;
    }

    // Populate the form fields
    form.querySelector('#edit-task-id').value = task.id;
    form.querySelector('#edit-task-title').value = task.title;
    form.querySelector('#edit-task-description').value = task.description || '';
    form.querySelector('#edit-task-priority').value = task.priority;
    form.querySelector('#edit-task-status').value = task.status;
    // Handle date format (input type="date" expects YYYY-MM-DD)
    form.querySelector('#edit-task-due-date').value = task.due_date || '';

    // Clear any previous messages
    const messageElement = form.querySelector('#edit-task-message');
    if (messageElement) {
        messageElement.textContent = '';
        messageElement.style.display = 'none';
    }

    // Display the modal
    modal.style.display = 'block';
}

/**
 * Closes the edit modal. (Can be called via onclick attribute or event listener)
 */
function closeEditModal() {
    const modal = document.getElementById('edit-task-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

/**
 * Handles the submission of the edit task form.
 */
async function handleEditTaskSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const taskId = form.querySelector('#edit-task-id').value;
    const messageElementId = 'edit-task-message';

    // Collect updated data from the form
    const updatedData = {
        task_id: taskId,
        title: form.querySelector('#edit-task-title').value.trim(),
        description: form.querySelector('#edit-task-description').value.trim(),
        priority: form.querySelector('#edit-task-priority').value,
        status: form.querySelector('#edit-task-status').value,
        due_date: form.querySelector('#edit-task-due-date').value || null // Send null if empty
    };

     if (!updatedData.title) {
        if (typeof showMessage === 'function') showMessage(messageElementId, 'Title cannot be empty.', 'error');
        else alert('Title cannot be empty.');
        return;
    }

    console.log("Submitting updated task data:", updatedData);
    if (typeof showMessage === 'function') showMessage(messageElementId, 'Saving changes...', 'info');

    try {
        const response = await fetch('api/tasks/update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(updatedData)
        });

        const result = await response.json();

        if (result.success) {
            console.log("Task updated successfully:", result);
            closeEditModal();
            loadTasks(); // Reload the task list to show changes
        } else {
            console.error("API error updating task:", result.message);
            if (typeof showMessage === 'function') showMessage(messageElementId, `Failed to update task: ${result.message}`, 'error');
            else alert(`Failed to update task: ${result.message}`);
        }
    } catch (error) {
        console.error("Could not update task:", error);
        if (typeof showMessage === 'function') showMessage(messageElementId, `Error updating task: ${error.message}.`, 'error');
        else alert(`Error updating task: ${error.message}.`);
    }
}


// Placeholder for Edit Task Modal functionality (Now implemented above)
// async function handleEditTask(taskId) {
//     console.log("Initiating edit for task:", taskId);
//     // 1. Fetch task details (optional, could pass data if available)
//     // 2. Populate an edit modal form
//     // 3. Show the modal
//     // 4. Add event listener to modal form submission
//     // 5. On submit, call update API with all fields
// }

// Utility to escape HTML (if not available from main.js)
/*
function escapeHTML(str) {
    if (str === null || str === undefined) return '';
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}
*/
