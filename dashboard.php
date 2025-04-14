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
            <!-- Filters/Search (to be implemented) -->
            <div class="filters">
                <!-- Filter/Search controls go here -->
            </div>

            <!-- Container where tasks will be loaded by JS -->
            <div id="task-list-container">
                <p>Loading tasks...</p>
            </div>
        </section>

        <!-- Modals for Edit Task etc. (to be implemented) -->
        <!-- <div id="edit-task-modal" class="modal"> ... </div> -->

    </div> <!-- /container -->
</main>

<?php
// Include footer
require_once 'includes/footer.php';
?>
