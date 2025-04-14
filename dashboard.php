<?php
require_once 'includes/functions.php';

// Ensure user is logged in to access the dashboard
isLoggedIn(true); // Redirects to login.php if not logged in

// Include header
$pageTitle = "Dashboard"; // Optional: Set a specific page title
require_once 'includes/header.php';

// Get user info from session if needed (e.g., for welcome message)
$username = isset($_SESSION['username']) ? escape($_SESSION['username']) : 'User';

?>

<!-- Header specific to dashboard (Example) -->
<header class="dashboard-header">
    <div class="container">
        <h1>My To-Do List</h1>
        <div class="user-menu">
            Welcome, <?php echo $username; ?>!
            <a href="api/auth/logout.php">Logout</a>
        </div>
    </div>
</header>

<main class="dashboard-main">
    <div class="container">

        <!-- Add Task Section (Example - could be a button opening a modal) -->
        <section class="add-task-section">
            <h2>Add New Task</h2>
            <!-- Add Task Form (to be implemented, possibly in a modal) -->
            <form id="add-task-form">
                <input type="text" id="new-task-title" placeholder="Task Title" required>
                <textarea id="new-task-description" placeholder="Description (Optional)"></textarea>
                <!-- Add priority, due date inputs -->
                <button type="submit">Add Task</button>
            </form>
        </section>

        <!-- Task List Section -->
        <section class="task-list-section">
            <h2>Your Tasks</h2>
            <!-- Filters/Search -->
            <div class="filters">
                <div class="filter-group">
                    <label for="filter-status">Status:</label>
                    <select id="filter-status" name="status">
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                 <div class="filter-group">
                    <label for="filter-priority">Priority:</label>
                    <select id="filter-priority" name="priority">
                        <option value="all">All</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                 <div class="filter-group">
                     <label for="search-term">Search:</label>
                    <input type="text" id="search-term" name="search" placeholder="Search title/description...">
                </div>
                 <button id="clear-filters-btn" class="button button-secondary" style="display: none;">Clear Filters</button> <!-- Initially hidden -->
            </div>

            <!-- Container where tasks will be loaded by JS -->
            <div id="task-list-container">
                <p>Loading tasks...</p>
            </div>
        </section>

        <!-- Edit Task Modal -->
        <div id="edit-task-modal" class="modal" style="display: none;"> <!-- Hidden by default -->
            <div class="modal-content">
                <span class="close-modal-btn" onclick="closeEditModal()">&times;</span>
                <h2>Edit Task</h2>
                <form id="edit-task-form">
                    <input type="hidden" id="edit-task-id" name="task_id">
                    <div class="form-group">
                        <label for="edit-task-title">Title:</label>
                        <input type="text" id="edit-task-title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-task-description">Description:</label>
                        <textarea id="edit-task-description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-task-priority">Priority:</label>
                        <select id="edit-task-priority" name="priority">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-task-due-date">Due Date:</label>
                        <input type="date" id="edit-task-due-date" name="due_date">
                    </div>
                    <div class="form-group">
                        <label for="edit-task-status">Status:</label>
                        <select id="edit-task-status" name="status">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <button type="submit">Save Changes</button>
                    <div id="edit-task-message" class="message" style="display: none;"></div>
                </form>
            </div>
        </div>
        <!-- End Edit Task Modal -->

    </div> <!-- /container -->
</main>

<?php
// Include footer
require_once 'includes/footer.php';
?>
