// JavaScript for Task Management (Dashboard Page)

// Assumes main.js is loaded first and provides escapeHTML and showMessage
// If not, define them here or ensure they are globally available.
// function escapeHTML(str) { ... }
// function showMessage(elementId, message, type = 'info') { ... }

// Store loaded tasks globally within this script's scope to access for editing
let currentTasks = [];
// Store the currently active status filter
let currentStatusFilter = 'all';

document.addEventListener('DOMContentLoaded', () => {
    // Only run task-specific code if we are on the dashboard
    const taskListContainer = document.getElementById('task-list-container');
    const addTaskForm = document.getElementById('add-task-form');
    const searchTermInput = document.getElementById('search-term');
    const sidebarNav = document.querySelector('.sidebar-nav'); // Get sidebar nav container

    if (taskListContainer) {
        console.log('Tasks JS Loaded');
        loadTasks(); // Initial load (fetches all tasks into currentTasks)
        setupEventListeners(taskListContainer, addTaskForm, sidebarNav, searchTermInput); // Pass relevant elements
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
            if (response.status === 401) {
                 console.error('Authentication error loading tasks.');
                 showMessage('task-list-container', 'Authentication error. Please login again.', 'error');
                 return;
            }
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const result = await response.json();

        if (result.success) {
            currentTasks = result.tasks;
            applyFiltersAndDisplay(); // Display initially filtered (or all) tasks
        } else {
            currentTasks = [];
            console.error("API error loading tasks:", result.message);
            showMessage('task-list-container', `Failed to load tasks: ${result.message}`, 'error');
            displayTasks([]);
        }
    } catch (error) {
        console.error("Could not load tasks:", error);
        showMessage('task-list-container', `Error loading tasks: ${error.message}.`, 'error');
        displayTasks([]);
    }
}

/**
 * Displays tasks in either list or grid view
 */
function displayTasks(tasks) {
    const container = document.getElementById('task-list-container');
    if (!container) return;

    const view = container.classList.contains('grid-view') ? 'grid' : 'list';
    container.innerHTML = '';

    if (tasks.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-tasks"></i>
                <h3>No tasks yet</h3>
                <p>Click "New Task" or press "/" to create your first task</p>
            </div>
        `;
        return;
    }

    const taskList = document.createElement('div');
    taskList.className = view === 'grid' ? 'task-grid' : 'task-list';

    tasks.forEach(task => {
        taskList.appendChild(createTaskElement(task, view));
    });

    container.appendChild(taskList);
    updateSidebarStats();

    // Apply animations
    if (typeof applyStaggeredAnimation === 'function') {
        applyStaggeredAnimation('.task-card', 'task-card-load-animation', 0.05);
    }
}

/**
 * Updates the task count statistics in the sidebar based on the global currentTasks.
 */
function updateSidebarStats() {
    const totalTasks = currentTasks.length;
    const totalPending = currentTasks.filter(task => task.status === 'pending').length;
    const totalCompleted = currentTasks.filter(task => task.status === 'completed').length;

    const statsTotalEl = document.getElementById('stats-total');
    const statsPendingEl = document.getElementById('stats-pending');
    const statsCompletedEl = document.getElementById('stats-completed');

    if (statsTotalEl) statsTotalEl.textContent = totalTasks;
    if (statsPendingEl) statsPendingEl.textContent = totalPending;
    if (statsCompletedEl) statsCompletedEl.textContent = totalCompleted;
}

/**
 * Creates a task card element in either list or grid view
 */
function createTaskElement(task, view = 'list') {
    const taskElement = document.createElement('div');
    taskElement.className = `task-card priority-${task.priority} status-${task.status}`;
    taskElement.dataset.taskId = task.id;

    const title = (typeof escapeHTML === 'function' ? escapeHTML(task.title) : task.title) || 'Untitled Task';
    const description = (typeof escapeHTML === 'function' ? escapeHTML(task.description || '') : (task.description || ''));
    const titleClass = task.status === 'completed' ? 'task-title task-complete-animation' : 'task-title';

    if (view === 'grid') {
        taskElement.innerHTML = `
            <div class="task-card-header">
                <div class="task-status">
                    <label class="custom-checkbox-label" title="Mark as ${task.status === 'completed' ? 'Pending' : 'Completed'}">
                        <input type="checkbox" class="complete-task-chk visually-hidden" data-id="${task.id}" ${task.status === 'completed' ? 'checked' : ''}>
                        <span class="custom-checkbox-visual"></span>
                    </label>
                </div>
                <div class="task-meta">
                    <span class="priority-label">${task.priority}</span>
                    ${task.due_date ? `<span class="due-date"><i class="fas fa-calendar"></i> ${task.due_date}</span>` : ''}
                </div>
            </div>
            <div class="task-content">
                <h4 class="${titleClass}">${title}</h4>
                ${description ? `<p class="task-description">${description}</p>` : ''}
            </div>
            <div class="task-actions">
                <button class="edit-task-btn icon-btn" title="Edit Task"><i class="fas fa-pencil-alt"></i></button>
                <button class="delete-task-btn icon-btn" title="Delete Task"><i class="fas fa-trash-alt"></i></button>
            </div>
        `;
    } else {
        taskElement.innerHTML = `
            <div class="task-card-content">
                <div class="task-header">
                    <label class="custom-checkbox-label" title="Mark as ${task.status === 'completed' ? 'Pending' : 'Completed'}">
                        <input type="checkbox" class="complete-task-chk visually-hidden" data-id="${task.id}" ${task.status === 'completed' ? 'checked' : ''}>
                        <span class="custom-checkbox-visual"></span>
                    </label>
                    <h4 class="${titleClass}">${title}</h4>
                </div>
                ${description ? `<p class="task-description">${description}</p>` : ''}
                <div class="task-meta">
                    <span class="priority-label">${task.priority}</span>
                    ${task.due_date ? `<span class="due-date"><i class="fas fa-calendar"></i> ${task.due_date}</span>` : ''}
                </div>
            </div>
            <div class="task-actions">
                <button class="edit-task-btn icon-btn" title="Edit Task"><i class="fas fa-pencil-alt"></i></button>
                <button class="delete-task-btn icon-btn" title="Delete Task"><i class="fas fa-trash-alt"></i></button>
            </div>
        `;
    }

    return taskElement;
}

/**
 * Sets up event listeners for task interactions.
 */
function setupEventListeners(taskContainer, addTaskForm, sidebarNav, searchTermInput) {
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

    // Sidebar navigation link (status filter) listener
    sidebarNav?.addEventListener('click', (event) => {
        if (event.target.classList.contains('status-filter')) {
            event.preventDefault(); // Prevent default link behavior
            const status = event.target.dataset.status;

            // Update active state visually
            sidebarNav.querySelectorAll('.status-filter').forEach(link => link.classList.remove('active'));
            event.target.classList.add('active');

            // Update global filter state and re-apply filters
            currentStatusFilter = status;
            applyFiltersAndDisplay();
        }
    });

    // Search input listener
    searchTermInput?.addEventListener('input', applyFiltersAndDisplay);

    // Edit form submission listener
    const editTaskForm = document.getElementById('edit-task-form');
    editTaskForm?.addEventListener('submit', handleEditTaskSubmit);

    // Modal close listeners
    const closeModalButton = document.querySelector('#edit-task-modal .close-modal-btn');
    closeModalButton?.addEventListener('click', closeEditModal);
    const modal = document.getElementById('edit-task-modal');
    modal?.addEventListener('click', (event) => {
        if (event.target === modal) closeEditModal();
    });
}

// --- Filtering Logic ---

/**
 * Applies current filters (status from active link, search term) and re-displays tasks.
 */
function applyFiltersAndDisplay() {
    const searchTerm = document.getElementById('search-term')?.value.toLowerCase().trim() || '';
    let filteredTasks = currentTasks;

    if (currentStatusFilter !== 'all') {
        filteredTasks = filteredTasks.filter(task => task.status === currentStatusFilter);
    }

    if (searchTerm) {
        filteredTasks = filteredTasks.filter(task =>
            task.title.toLowerCase().includes(searchTerm) ||
            (task.description && task.description.toLowerCase().includes(searchTerm))
        );
    }

    displayTasks(filteredTasks);
}

// Add event listener for view switching
document.addEventListener('DOMContentLoaded', () => {
    const viewButtons = document.querySelectorAll('.view-btn');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const container = document.getElementById('task-list-container');
            if (container) {
                container.className = 'tasks-container ' + btn.dataset.view + '-view';
                applyFiltersAndDisplay(); // Redisplay tasks in new view
            }
        });
    });
});

// --- CRUD Handlers ---

/**
 * Handles the submission of the 'Add Task' form.
 */
async function handleAddTask(event) {
    event.preventDefault();
    const form = event.target;
    const titleInput = form.querySelector('#new-task-title');
    const descriptionInput = form.querySelector('#new-task-description');
    const messageElementId = 'add-task-message';
    const submitButton = form.querySelector('button[type="submit"]');

    const taskData = {
        title: titleInput.value.trim(),
        description: descriptionInput.value.trim(),
    };

    if (!taskData.title) {
        if (typeof showMessage === 'function') showMessage(messageElementId, 'Task title cannot be empty.', 'error');
        else alert('Task title cannot be empty.');
        return;
    }

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
            form.reset();
            if (typeof showMessage === 'function') {
                const msgElement = document.getElementById(messageElementId);
                if(msgElement) msgElement.style.display = 'none';
            }
            await loadTasks(); // Reload and apply filters
        } else {
            if (typeof showMessage === 'function') showMessage(messageElementId, `Failed to add task: ${result.message}`, 'error');
            else alert(`Failed to add task: ${result.message}`);
        }
    } catch (error) {
        if (typeof showMessage === 'function') showMessage(messageElementId, `Error adding task: ${error.message}.`, 'error');
        else alert(`Error adding task: ${error.message}.`);
    } finally {
        if(submitButton) {
            submitButton.disabled = false;
            submitButton.classList.remove('loading');
        }
    }
}

/**
 * Handles deleting a task with animation.
 */
async function handleDeleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) return;

    const taskElement = document.querySelector(`.task-card[data-task-id="${taskId}"]`);

    try {
        const response = await fetch('api/tasks/delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ task_id: taskId })
        });
        const result = await response.json();

        if (result.success) {
            if (taskElement) {
                taskElement.classList.add('task-delete-fade-out');
                taskElement.addEventListener('animationend', () => {
                    taskElement.remove();
                    currentTasks = currentTasks.filter(task => task.id != taskId);
                    applyFiltersAndDisplay(); // Re-render
                }, { once: true });
            } else {
                 await loadTasks(); // Fallback reload
            }
        } else {
            alert(`Failed to delete task: ${result.message}`);
        }
    } catch (error) {
        alert(`Error deleting task: ${error.message}.`);
    }
}

/**
 * Handles toggling the completion status of a task.
 */
async function handleToggleComplete(taskId, isComplete) {
    const newStatus = isComplete ? 'completed' : 'pending';
    const updateData = { task_id: taskId, status: newStatus };

    try {
        const response = await fetch('api/tasks/update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(updateData)
        });
        const result = await response.json();

        if (result.success) {
            const taskIndex = currentTasks.findIndex(t => t.id == taskId);
            if (taskIndex > -1) {
                currentTasks[taskIndex].status = newStatus;
            }
            applyFiltersAndDisplay(); // Re-render
        } else {
            alert(`Failed to update task status: ${result.message}`);
            const checkbox = document.querySelector(`.task-card[data-task-id="${taskId}"] .complete-task-chk`);
            if (checkbox) checkbox.checked = !isComplete; // Revert UI
        }
    } catch (error) {
        alert(`Error updating task status: ${error.message}.`);
        const checkbox = document.querySelector(`.task-card[data-task-id="${taskId}"] .complete-task-chk`);
        if (checkbox) checkbox.checked = !isComplete; // Revert UI
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
            closeEditModal();
            await loadTasks(); // Reload tasks to reflect changes
        } else {
            if (typeof showMessage === 'function') showMessage(messageElementId, `Failed to update task: ${result.message}`, 'error');
            else alert(`Failed to update task: ${result.message}`);
        }
    } catch (error) {
        if (typeof showMessage === 'function') showMessage(messageElementId, `Error updating task: ${error.message}.`, 'error');
        else alert(`Error updating task: ${error.message}.`);
    } finally {
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
