<?php
// Include necessary files
require_once 'includes/config.php'; // Load config first (for language)
require_once 'includes/functions.php';

// Ensure user is logged in to access the dashboard
isLoggedIn(true); // Redirects to login.php if not logged in

// Set page title key for translation
$pageTitleKey = "page_title_dashboard";
require_once 'includes/header.php';

// Get user info from session if needed (e.g., for welcome message - already handled in header)
// $username = isset($_SESSION['username']) ? escape($_SESSION['username']) : 'User';

?>
<div class="dashboard-layout"> <!-- New wrapper for sidebar + main -->

    <aside class="dashboard-sidebar">
        <h3><?php echo t('dashboard_sidebar_view'); ?></h3>
        <ul class="sidebar-nav">
            <li><a href="#" class="status-filter active" data-status="all"><i class="fas fa-list-ul fa-fw"></i> <?php echo t('dashboard_sidebar_all_tasks'); ?></a></li>
            <li><a href="#" class="status-filter" data-status="pending"><i class="fas fa-clock fa-fw"></i> <?php echo t('dashboard_sidebar_pending'); ?></a></li>
            <li><a href="#" class="status-filter" data-status="completed"><i class="fas fa-check-circle fa-fw"></i> <?php echo t('dashboard_sidebar_completed'); ?></a></li>
        </ul>
        <hr>
         <!-- Search Moved Here -->
         <div class="sidebar-search">
             <h3><?php echo t('dashboard_sidebar_search'); ?></h3>
              <div class="filter-group">
                  <!-- <label for="search-term">Search:</label> --> <!-- Label might be redundant now -->
                 <input type="text" id="search-term" name="search" placeholder="<?php echo t('dashboard_sidebar_search_placeholder'); ?>">
             </div>
             <!-- Clear button might be less necessary now, or handled differently -->
             <!-- <button id="clear-filters-btn" class="button button-secondary" style="display: none; width: 100%; margin-top: 10px;">Clear Filters</button> -->
         </div>
         <hr>
         <!-- Stats Section -->
         <div class="sidebar-stats">
             <h3><?php echo t('dashboard_sidebar_stats'); ?></h3>
             <p><?php echo t('dashboard_sidebar_stats_total'); ?> <span id="stats-total">0</span></p>
             <p><?php echo t('dashboard_sidebar_stats_pending'); ?> <span id="stats-pending">0</span></p>
             <p><?php echo t('dashboard_sidebar_stats_completed'); ?> <span id="stats-completed">0</span></p>
         </div>
    </aside>

    <main class="dashboard-main">
        <!-- Removed the outer .container div, will apply to sections if needed -->

        <!-- Add Task Section -->
        <section class="add-task-section">
            <h2><?php echo t('dashboard_add_task_title'); ?></h2>
            <!-- Add Task Form (to be implemented, possibly in a modal) -->
            <form id="add-task-form">
                <input type="text" id="new-task-title" placeholder="<?php echo t('dashboard_add_task_placeholder_title'); ?>" required>
                <textarea id="new-task-description" placeholder="<?php echo t('dashboard_add_task_placeholder_desc'); ?>"></textarea>
                <!-- Add priority, due date inputs -->
                <button type="submit"><?php echo t('dashboard_add_task_button'); ?></button>
            </form>
        </section>

        <!-- Task List Section -->
        <section class="task-list-section">
            <h2><?php echo t('dashboard_task_list_title'); ?></h2>
            <!-- Filters moved to sidebar -->

            <!-- Container where tasks will be loaded by JS -->
            <div id="task-list-container">
                <p>Loading tasks...</p> <!-- JS will handle this -->
            </div>
        </section>

        <!-- Edit Task Modal -->
        <div id="edit-task-modal" class="modal" style="display: none;"> <!-- Hidden by default -->
            <div class="modal-content">
                <span class="close-modal-btn" onclick="closeEditModal()">&times;</span>
                <h2><?php echo t('dashboard_edit_modal_title'); ?></h2>
                <form id="edit-task-form">
                    <input type="hidden" id="edit-task-id" name="task_id">
                    <div class="form-group">
                        <label for="edit-task-title"><?php echo t('dashboard_edit_modal_label_title'); ?></label>
                        <input type="text" id="edit-task-title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-task-description"><?php echo t('dashboard_edit_modal_label_desc'); ?></label>
                        <textarea id="edit-task-description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-task-priority"><?php echo t('dashboard_edit_modal_label_priority'); ?></label>
                        <select id="edit-task-priority" name="priority">
                            <option value="low"><?php echo t('dashboard_edit_modal_priority_low'); ?></option>
                            <option value="medium"><?php echo t('dashboard_edit_modal_priority_medium'); ?></option>
                            <option value="high"><?php echo t('dashboard_edit_modal_priority_high'); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-task-due-date"><?php echo t('dashboard_edit_modal_label_due_date'); ?></label>
                        <input type="date" id="edit-task-due-date" name="due_date">
                    </div>
                    <div class="form-group">
                        <label for="edit-task-status"><?php echo t('dashboard_edit_modal_label_status'); ?></label>
                        <select id="edit-task-status" name="status">
                            <option value="pending"><?php echo t('dashboard_edit_modal_status_pending'); ?></option>
                            <option value="completed"><?php echo t('dashboard_edit_modal_status_completed'); ?></option>
                        </select>
                    </div>
                    <button type="submit"><?php echo t('dashboard_edit_modal_button_save'); ?></button>
                    <div id="edit-task-message" class="message" style="display: none;"></div>
                </form>
            </div>
        </div>
        <!-- End Edit Task Modal -->

    </main>

</div> <!-- /dashboard-layout -->

<?php
// Include footer
require_once 'includes/footer.php';
?>
