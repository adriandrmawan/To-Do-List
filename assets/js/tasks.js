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
    const addTaskForm = document.getElementById('add-task-form');
    const filterStatus = document.getElementById('filter-status');
    const filterPriority = document.getElementById('filter-priority');
    const searchTermInput = document.getElementById('search-term');
    const clearFiltersBtn = document.getElementById('clear-filters-btn');

    if (taskListContainer) {
        console.log('Tasks JS Loaded');
        loadTasks(); // Initial load (fetches all tasks into currentTasks)
        setupEventListeners(taskListContainer, addTaskForm, filterStatus, filterPriority, searchTermInput, clearFiltersBtn); // Pass filter elements
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
            // Apply filters *after* fetching all tasks
            applyFiltersAndDisplay(); // Display initially filtered (or all) tasks
        } else {
            currentTasks = []; // Clear tasks on error
            console.error("API error loading tasks:", result.message);
            showMessage('task-list-container', `Failed to load tasks: ${result.message}`, 'error');
            displayTasks([]); // Display empty state
        }
    } catch (error) {
        console.error("Could not load tasks:", error);
        showMessage('task-list-container', `Error loading tasks: ${error.message}.`, 'error');
        displayTasks([]); // Display empty state
    }
}

/**
 * Displays tasks grouped by status in the UI.
 * @param {Array} tasks Array of task objects to display (potentially filtered).
 */
function displayTasks(tasks) {
    const container = document.getElementById('task-list-container');
    if (!container) return;

    container.innerHTML = ''; // Clear loading message or previous tasks

    // Group tasks
    const pendingTasks = tasks.filter(task => task.status === 'pending');
    const completedTasks = tasks.filter(task => task.status === 'completed');

    // Create sections for pending and completed tasks
    const pendingSection = document.createElement('div');
    pendingSection.id = 'pending-tasks';
    pendingSection.innerHTML = '<h3>Pending</h3>';
    const pendingGrid = document.createElement('div');
    pendingGrid.className = 'task-grid';
    if (pendingTasks.length === 0) {
        // Add message directly to section if grid is empty
         if (!container.querySelector('.no-tasks-message')) { // Check if overall container is empty first
             pendingSection.innerHTML += '<p class="no-tasks-message">No pending tasks.</p>';
         }
    } else {
        pendingTasks.forEach(task => pendingGrid.appendChild(createTaskElement(task)));
        pendingSection.appendChild(pendingGrid);
    }

    const completedSection = document.createElement('div');
    completedSection.id = 'completed-tasks';
    completedSection.innerHTML = '<h3>Completed</h3>';
    const completedGrid = document.createElement('div');
    completedGrid.className = 'task-grid';
     if (completedTasks.length === 0) {
         if (!container.querySelector('.no-tasks-message')) {
            completedSection.innerHTML += '<p class="no-tasks-message">No completed tasks.</p>';
         }
    } else {
        completedTasks.forEach(task => completedGrid.appendChild(createTaskElement(task)));
        completedSection.appendChild(completedGrid);
    }

    // Only add sections if they contain tasks or if there are no tasks at all
    if (tasks.length === 0) {
         container.innerHTML = '<p class="no-tasks-message">No tasks match the current filters.</p>';
    } else {
        container.appendChild(pendingSection);
        container.appendChild(completedSection);
    }


    // Apply staggered load animation to newly displayed cards
    if (typeof applyStaggeredAnimation === 'function') {
        // Apply to cards within the grids
        applyStaggeredAnimation('#pending-tasks .task-grid .task-card', 'task-card-load-animation', 0.05);
        applyStaggeredAnimation('#completed-tasks .task-grid .task-card', 'task-card-load-animation', 0.05);
    } else {
        console.warn('applyStaggeredAnimation function not found. Skipping card load animations.');
    }
}

/**
 * Creates the HTML element for a single task.
 * @param {object} task The task object.
 * @returns {HTMLElement} The task card element.
 */
function createTaskElement(task) {
    const taskElement = document.createElement('div');
    taskElement.className = `task-card priority-${task.priority} status-${task.status}`;
    taskElement.dataset.taskId = task.id;

    const title = (typeof escapeHTML === 'function' ? escapeHTML(task.title) : task.title) || 'Untitled Task';
    const description = (typeof escapeHTML === 'function' ? escapeHTML(task.description || '') : (task.description || ''));

    // Add completion animation class if task is completed
    const titleClass = task.status === 'completed' ? 'task-title task-complete-animation' : 'task-title';

    taskElement.innerHTML = `
        <div class="task-card-content">
            <h4 class="${titleClass}">${title}</h4>
            ${description ? `<p class="task-description">${description}</p>` : ''}
            <div class="task-meta">
                <span>Priority: <span class="priority-label">${task.priority}</span></span>
                ${task.due_date ? `<span>Due: ${task.due_date}</span>` : ''}
            </div>
        </div>
        <div class="task-card-actions">
            <button class="edit-task-btn icon-btn" title="Edit Task" data-id="${task.id}"><i class="fas fa-pencil-alt"></i></button>
            <button class="delete-task-btn icon-btn" title="Delete Task" data-id="${task.id}"><i class="fas fa-trash-alt"></i></button>
            <label class="custom-checkbox-label" title="Mark as ${task.status === 'completed' ? 'Pending' : 'Completed'}">
                <input type="checkbox" class="complete-task-chk visually-hidden" data-id="${task.id}" ${task.status === 'completed' ? 'checked' : ''}>
                <span class="custom-checkbox-visual"></span>
            </label>
        </div>
    `;
    return taskElement;
}


/**
 * Sets up event listeners for task interactions.
 */
function setupEventListeners(taskContainer, addTaskForm, filterStatus, filterPriority, searchTermInput, clearFiltersBtn) {
    // Add Task Form Submission
    addTaskForm?.addEventListener('submit', handleAddTask);

    // Event delegation for task card actions
    taskContainer?.addEventListener('click', (event) => {
        const target = event.target;
        const taskCard = target.closest('.task-card');
        const taskId = taskCard?.dataset.taskId;

        if (!taskId) return;

        if (target.closest('.edit-task-btn')) {
             openEditModal(taskId);
        } else if (target.closest('.delete-task-btn')) {
             handleDeleteTask(taskId);
        } else if (target.classList.contains('complete-task-chk')) {
             handleToggleComplete(taskId, target.checked);
        }
    });

    // Filter controls listeners
    filterStatus?.addEventListener('change', applyFiltersAndDisplay);
    filterPriority?.addEventListener('change', applyFiltersAndDisplay);
    searchTermInput?.addEventListener('input', applyFiltersAndDisplay); // Real-time search
    clearFiltersBtn?.addEventListener('click', () => {
        if(filterStatus) filterStatus.value = 'all';
        if(filterPriority) filterPriority.value = 'all';
        if(searchTermInput) searchTermInput.value = '';
        applyFiltersAndDisplay();
    });

    // Edit form submission listener
    const editTaskForm = document.getElementById('edit-task-form');
    editTaskForm?.addEventListener('submit', handleEditTaskSubmit);

    // Listener for closing the modal (can also use onclick in HTML)
    const closeModalButton = document.querySelector('#edit-task-modal .close-modal-btn');
    closeModalButton?.addEventListener('click', closeEditModal);

    // Optional: Close modal if clicking outside the content
    const modal = document.getElementById('edit-task-modal');
    modal?.addEventListener('click', (event) => {
        if (event.target === modal) { // Check if click is on the backdrop
            closeEditModal();
        }
    });
}

// --- Filtering Logic ---

/**
 * Applies current filters to the tasks and re-displays them.
 */
function applyFiltersAndDisplay() {
    const statusFilter = document.getElementById('filter-status')?.value || 'all';
    const priorityFilter = document.getElementById('filter-priority')?.value || 'all';
    const searchTerm = document.getElementById('search-term')?.value.toLowerCase().trim() || '';
    const clearFiltersBtn = document.getElementById('clear-filters-btn');

    const filtersActive = statusFilter !== 'all' || priorityFilter !== 'all' || searchTerm !== '';
    if (clearFiltersBtn) {
        clearFiltersBtn.style.display = filtersActive ? 'inline-block' : 'none';
    }

    let filteredTasks = currentTasks;

    if (statusFilter !== 'all') {
        filteredTasks = filteredTasks.filter(task => task.status === statusFilter);
    }
    if (priorityFilter !== 'all') {
        filteredTasks = filteredTasks.filter(task => task.priority === priorityFilter);
    }
    if (searchTerm) {
        filteredTasks = filteredTasks.filter(task =>
            task.title.toLowerCase().includes(searchTerm) ||
            (task.description && task.description.toLowerCase().includes(searchTerm))
        );
    }

    displayTasks(filteredTasks); // Re-display using the filtered list
}


// --- CRUD Handlers ---

/**
 * Handles the submission of the 'Add Task' form.
 */
async function handleAddTask(event) {
    event.preventDefault();
    const form = event.target;
    const titleInput = form.querySelector('#new-task-title');
    const descriptionInput = form.querySelector('#new-task-description');
    const messageElementId = 'add-task-message'; // Assuming an element for messages
    const submitButton = form.querySelector('button[type="submit"]');

    const taskData = {
        title: titleInput.value.trim(),
        description: descriptionInput.value.trim(),
        // Add priority/due_date if form fields exist
    };

    if (!taskData.title) {
        if (typeof showMessage === 'function') showMessage(messageElementId, 'Task title cannot be empty.', 'error');
        else alert('Task title cannot be empty.');
        return;
    }

    console.log("Adding task:", taskData);
    if(submitButton) {
        submitButton.disabled = true;
        submitButton.classList.add('loading');
    }

    try {
        const response = await fetch('api/tasks/create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(taskData)
        });
        const result = await response.json();

        if (result.success) {
            console.log("Task added successfully:", result);
            form.reset();
            if (typeof showMessage === 'function') {
                const msgElement = document.getElementById(messageElementId);
                if(msgElement) msgElement.style.display = 'none'; // Hide message area
            }
            await loadTasks(); // Fetch all tasks again and apply filters
        } else {
            console.error("API error adding task:", result.message);
            if (typeof showMessage === 'function') showMessage(messageElementId, `Failed to add task: ${result.message}`, 'error');
            else alert(`Failed to add task: ${result.message}`);
        }
    } catch (error) {
        console.error("Could not add task:", error);
        if (typeof showMessage === 'function') showMessage(messageElementId, `Error adding task: ${error.message}.`, 'error');
        else alert(`Error adding task: ${error.message}.`);
    } finally {
        // Ensure button is re-enabled and loading class removed
        if(submitButton) {
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
        }
    }
}

/**
 * Handles deleting a task with animation.
 * @param {string} taskId The ID of the task to delete.
 */
async function handleDeleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) return;

    console.log("Deleting task:", taskId);
    const taskElement = document.querySelector(`.task-card[data-task-id="${taskId}"]`);

    try {
        const response = await fetch('api/tasks/delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ task_id: taskId })
        });
        const result = await response.json();

        if (result.success) {
            console.log("Task deleted successfully");
            if (taskElement) {
                taskElement.classList.add('task-delete-fade-out'); // Add animation class
                // Remove after animation
                taskElement.addEventListener('animationend', () => {
                    taskElement.remove();
                    // Update global task list and re-apply filters/display
                    currentTasks = currentTasks.filter(task => task.id != taskId);
                    applyFiltersAndDisplay(); // Re-render the list which handles empty messages
                }, { once: true }); // Ensure listener runs only once
            } else {
                 // If element somehow not found, just reload
                 await loadTasks();
            }
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

    const updateData = { task_id: taskId, status: newStatus };

    try {
        const response = await fetch('api/tasks/update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(updateData)
        });
        const result = await response.json();

        if (result.success) {
            console.log("Task status updated successfully");
            // Update local data and re-render is faster than full reload
            const taskIndex = currentTasks.findIndex(t => t.id == taskId);
            if (taskIndex > -1) {
                currentTasks[taskIndex].status = newStatus;
            }
            applyFiltersAndDisplay(); // Re-render with updated status
        } else {
            console.error("API error updating task status:", result.message);
            alert(`Failed to update task status: ${result.message}`);
            // Revert checkbox state visually on failure
            const checkbox = document.querySelector(`.task-card[data-task-id="${taskId}"] .complete-task-chk`);
            if (checkbox) checkbox.checked = !isComplete;
        }
    } catch (error) {
        console.error("Could not update task status:", error);
        alert(`Error updating task status: ${error.message}.`);
        const checkbox = document.querySelector(`.task-card[data-task-id="${taskId}"] .complete-task-chk`);
        if (checkbox) checkbox.checked = !isComplete;
    }
}

// --- Edit Modal Functions ---

/**
 * Opens the edit modal and populates it with task data.
 */
function openEditModal(taskId) {
    const modal = document.getElementById('edit-task-modal');
    const form = document.getElementById('edit-task-form');
    if (!modal || !form) return console.error("Edit modal or form not found!");

    const task = currentTasks.find(t => t.id == taskId);
    if (!task) return alert("Could not find task data to edit.");

    form.querySelector('#edit-task-id').value = task.id;
    form.querySelector('#edit-task-title').value = task.title;
    form.querySelector('#edit-task-description').value = task.description || '';
    form.querySelector('#edit-task-priority').value = task.priority;
    form.querySelector('#edit-task-status').value = task.status;
    form.querySelector('#edit-task-due-date').value = task.due_date || '';

    const messageElement = form.querySelector('#edit-task-message');
    if (messageElement) messageElement.style.display = 'none';

    modal.style.display = 'block';
}

/**
 * Closes the edit modal.
 */
function closeEditModal() {
    const modal = document.getElementById('edit-task-modal');
    if (modal) modal.style.display = 'none';
}

/**
 * Handles the submission of the edit task form.
 */
async function handleEditTaskSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const taskId = form.querySelector('#edit-task-id').value;
    const messageElementId = 'edit-task-message';
    const submitButton = form.querySelector('button[type="submit"]');

    const updatedData = {
        task_id: taskId,
        title: form.querySelector('#edit-task-title').value.trim(),
        description: form.querySelector('#edit-task-description').value.trim(),
        priority: form.querySelector('#edit-task-priority').value,
        status: form.querySelector('#edit-task-status').value,
        due_date: form.querySelector('#edit-task-due-date').value || null
    };

     if (!updatedData.title) {
        if (typeof showMessage === 'function') showMessage(messageElementId, 'Title cannot be empty.', 'error');
        else alert('Title cannot be empty.');
        return;
    }

    console.log("Submitting updated task data:", updatedData);
    if (typeof showMessage === 'function') showMessage(messageElementId, 'Saving changes...', 'info');
    if(submitButton) {
        submitButton.disabled = true;
        submitButton.classList.add('loading');
    }

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
            await loadTasks(); // Reload tasks to reflect changes
        } else {
            console.error("API error updating task:", result.message);
            if (typeof showMessage === 'function') showMessage(messageElementId, `Failed to update task: ${result.message}`, 'error');
            else alert(`Failed to update task: ${result.message}`);
        }
    } catch (error) {
        console.error("Could not update task:", error);
        if (typeof showMessage === 'function') showMessage(messageElementId, `Error updating task: ${error.message}.`, 'error');
        else alert(`Error updating task: ${error.message}.`);
    } finally {
         // Ensure button is re-enabled and loading class removed
         if(submitButton) {
             submitButton.disabled = false;
             submitButton.classList.remove('loading');
         }
    }
}

// Utility to escape HTML (if not available from main.js)
/*
function escapeHTML(str) {
    if (str === null || str === undefined) return '';
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}
*/
