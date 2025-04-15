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
    console.log(t_js('js_loading_tasks')); // Console log can remain in English or be translated
    const container = document.getElementById('task-list-container');
    if (!container) return;
    container.innerHTML = `<p>${t_js('js_loading_tasks')}</p>`; // Show loading state

    try {
        const response = await fetch('api/tasks/read.php'); // GET request by default
        if (!response.ok) {
            if (response.status === 401) {
                 console.error(t_js('js_auth_error_load'));
                 showMessage('task-list-container', t_js('js_auth_error_login'), 'error');
                 return;
            }
            // Use template literal for error message construction if needed, or pass status to t_js
            throw new Error(t_js('js_http_error', response.status));
        }
        const result = await response.json();

        if (result.success) {
            currentTasks = result.tasks;
            applyFiltersAndDisplay(); // Display initially filtered (or all) tasks
        } else {
            currentTasks = [];
            console.error(t_js('js_api_error_load', result.message));
            showMessage('task-list-container', t_js('js_fail_load_tasks', result.message), 'error');
            displayTasks([]);
        }
    } catch (error) {
        console.error("Could not load tasks:", error); // Keep console error in English for debugging
        showMessage('task-list-container', t_js('js_error_load_tasks', error.message), 'error');
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
                <h3>${t_js('js_no_tasks_yet')}</h3>
                <p>${t_js('js_no_tasks_prompt')}</p>
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

    // Use t_js for default title if task.title is empty
    const title = (typeof escapeHTML === 'function' ? escapeHTML(task.title) : task.title) || t_js('js_untitled_task');
    const description = (typeof escapeHTML === 'function' ? escapeHTML(task.description || '') : (task.description || ''));
    const titleClass = task.status === 'completed' ? 'task-title task-complete-animation' : 'task-title';
    const checkboxTitle = t_js(task.status === 'completed' ? 'js_mark_as_pending' : 'js_mark_as_completed');
    const editBtnTitle = t_js('js_edit_task_title');
    const deleteBtnTitle = t_js('js_delete_task_title');


    if (view === 'grid') {
        taskElement.innerHTML = `
            <div class="task-card-header">
                <div class="task-status">
                    <label class="custom-checkbox-label" title="${checkboxTitle}">
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
                <button class="edit-task-btn icon-btn" title="${editBtnTitle}"><i class="fas fa-pencil-alt"></i></button>
                <button class="delete-task-btn icon-btn" title="${deleteBtnTitle}"><i class="fas fa-trash-alt"></i></button>
            </div>
        `;
    } else {
        taskElement.innerHTML = `
            <div class="task-card-content">
                <div class="task-header">
                    <label class="custom-checkbox-label" title="${checkboxTitle}">
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
                <button class="edit-task-btn icon-btn" title="${editBtnTitle}"><i class="fas fa-pencil-alt"></i></button>
                <button class="delete-task-btn icon-btn" title="${deleteBtnTitle}"><i class="fas fa-trash-alt"></i></button>
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
        const errorMsg = t_js('js_task_title_empty');
        if (typeof showMessage === 'function') showMessage(messageElementId, errorMsg, 'error');
        else alert(errorMsg);
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
            const errorMsg = t_js('js_fail_add_task', result.message);
            if (typeof showMessage === 'function') showMessage(messageElementId, errorMsg, 'error');
            else alert(errorMsg);
        }
    } catch (error) {
        const errorMsg = t_js('js_error_add_task', error.message);
        if (typeof showMessage === 'function') showMessage(messageElementId, errorMsg, 'error');
        else alert(errorMsg);
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
    if (!confirm(t_js('js_confirm_delete_task'))) return;

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
            alert(t_js('js_fail_delete_task', result.message));
        }
    } catch (error) {
        alert(t_js('js_error_delete_task', error.message));
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
            alert(t_js('js_fail_update_status', result.message));
            const checkbox = document.querySelector(`.task-card[data-task-id="${taskId}"] .complete-task-chk`);
            if (checkbox) checkbox.checked = !isComplete; // Revert UI
        }
    } catch (error) {
        alert(t_js('js_error_update_status', error.message));
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
    if (!modal || !form) return console.error("Edit modal or form not found!"); // Keep console error in English

    const task = currentTasks.find(t => t.id == taskId);
    if (!task) return alert(t_js('js_edit_task_not_found'));

    form.querySelector('#edit-task-id').value = task.id;
    form.querySelector('#edit-task-title').value = task.title; // Populate with existing data, not translation key
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
        const errorMsg = t_js('js_task_title_empty'); // Reuse title empty message
        if (typeof showMessage === 'function') showMessage(messageElementId, errorMsg, 'error');
        else alert(errorMsg);
        return;
    }

    if (typeof showMessage === 'function') showMessage(messageElementId, t_js('js_saving_changes'), 'info');
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
            const errorMsg = t_js('js_fail_update_task', result.message);
            if (typeof showMessage === 'function') showMessage(messageElementId, errorMsg, 'error');
            else alert(errorMsg);
        }
    } catch (error) {
        const errorMsg = t_js('js_error_update_task', error.message);
        if (typeof showMessage === 'function') showMessage(messageElementId, errorMsg, 'error');
        else alert(errorMsg);
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
