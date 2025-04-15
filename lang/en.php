<?php
// lang/en.php - English Translations

return [
    // Header
    'app_name' => 'To-Do App',
    'login_link' => 'Sign In',
    'register_button' => 'Sign Up',
    'welcome_message' => 'Welcome, %s!', // %s is placeholder for username
    'logout_link' => 'Logout',

    // Add more keys as needed for other pages/elements
    'page_title_default' => 'To-Do App',
    'page_title_dashboard' => 'Dashboard',
    'page_title_login' => 'Login',
    'page_title_register' => 'Register',
    'page_title_index' => 'ToDo App - Organize Your Life',

    // Index Page (Landing)
    'index_hero_title' => 'To-Do. Simplified.',
    'index_hero_subtitle' => 'Focus on what matters most with our elegantly designed task management solution.',
    'index_hero_cta' => 'Get Started Free',
    'index_features_title' => 'Simply Powerful',
    'index_feature1_title' => 'Quick Add',
    'index_feature1_desc' => 'Add tasks in seconds with our streamlined interface designed for speed and efficiency.',
    'index_feature2_title' => 'Instant Progress',
    'index_feature2_desc' => 'Mark tasks complete with a single click and feel the satisfaction of getting things done.',
    'index_feature3_title' => 'Beautiful Design',
    'index_feature3_desc' => 'Enjoy a thoughtfully crafted experience with attention to every detail, inspired by Apple design.',
    'index_cta_title' => 'Start Organizing Today',
    'index_cta_subtitle' => 'Experience a new level of productivity with our beautifully simple to-do list app.',
    'index_cta_button' => 'Create Account',

    // Login Page
    'login_heading' => 'Sign In',
    'login_label_username' => 'Username or Email',
    'login_label_password' => 'Password',
    'login_button_submit' => 'Sign In',
    'login_switch_text' => "Don't have an account?",
    'login_switch_link' => 'Sign Up', // Reusing register_button might be confusing, using specific link text

    // Register Page
    'register_heading' => 'Create Account',
    'register_label_username' => 'Username',
    'register_label_email' => 'Email',
    'register_label_password' => 'Password',
    'register_label_confirm_password' => 'Confirm Password',
    'register_button_submit' => 'Sign Up',
    'register_switch_text' => 'Already have an account?',
    'register_switch_link' => 'Sign In', // Reusing login_link

    // Dashboard Page (Static PHP parts)
    'dashboard_sidebar_view' => 'View',
    'dashboard_sidebar_all_tasks' => 'All Tasks',
    'dashboard_sidebar_pending' => 'Pending',
    'dashboard_sidebar_completed' => 'Completed',
    'dashboard_sidebar_search' => 'Search',
    'dashboard_sidebar_search_placeholder' => 'Search tasks...',
    'dashboard_sidebar_stats' => 'Stats',
    'dashboard_sidebar_stats_total' => 'Total:',
    'dashboard_sidebar_stats_pending' => 'Pending:',
    'dashboard_sidebar_stats_completed' => 'Completed:',
    'dashboard_add_task_title' => 'Add New Task',
    'dashboard_add_task_placeholder_title' => 'Task Title',
    'dashboard_add_task_placeholder_desc' => 'Description (Optional)',
    'dashboard_add_task_button' => 'Add Task',
    'dashboard_task_list_title' => 'Your Tasks',
    'dashboard_edit_modal_title' => 'Edit Task',
    'dashboard_edit_modal_label_title' => 'Title:',
    'dashboard_edit_modal_label_desc' => 'Description:',
    'dashboard_edit_modal_label_priority' => 'Priority:',
    'dashboard_edit_modal_priority_low' => 'Low',
    'dashboard_edit_modal_priority_medium' => 'Medium',
    'dashboard_edit_modal_priority_high' => 'High',
    'dashboard_edit_modal_label_due_date' => 'Due Date:',
    'dashboard_edit_modal_label_status' => 'Status:',
    'dashboard_edit_modal_status_pending' => 'Pending',
    'dashboard_edit_modal_status_completed' => 'Completed',
    'dashboard_edit_modal_button_save' => 'Save Changes',

    // JavaScript Translations (tasks.js & potentially main.js)
    'js_loading_tasks' => 'Loading tasks...',
    'js_auth_error_load' => 'Authentication error loading tasks.',
    'js_auth_error_login' => 'Authentication error. Please login again.',
    'js_http_error' => 'HTTP error! Status: %s', // %s for status code
    'js_api_error_load' => 'API error loading tasks: %s', // %s for message
    'js_fail_load_tasks' => 'Failed to load tasks: %s', // %s for message
    'js_error_load_tasks' => 'Error loading tasks: %s.', // %s for message
    'js_no_tasks_yet' => 'No tasks yet',
    'js_no_tasks_prompt' => 'Click "Add New Task" or press "/" to create your first task', // Updated key for add task button
    'js_mark_as_pending' => 'Mark as Pending',
    'js_mark_as_completed' => 'Mark as Completed',
    'js_edit_task_title' => 'Edit Task',
    'js_delete_task_title' => 'Delete Task',
    'js_task_title_empty' => 'Task title cannot be empty.',
    'js_fail_add_task' => 'Failed to add task: %s', // %s for message
    'js_error_add_task' => 'Error adding task: %s.', // %s for message
    'js_confirm_delete_task' => 'Are you sure you want to delete this task?',
    'js_fail_delete_task' => 'Failed to delete task: %s', // %s for message
    'js_error_delete_task' => 'Error deleting task: %s.', // %s for message
    'js_fail_update_status' => 'Failed to update task status: %s', // %s for message
    'js_error_update_status' => 'Error updating task status: %s.', // %s for message
    'js_edit_task_not_found' => 'Could not find task data to edit.',
    'js_saving_changes' => 'Saving changes...',
    'js_fail_update_task' => 'Failed to update task: %s', // %s for message
    'js_error_update_task' => 'Error updating task: %s.', // %s for message
    'js_untitled_task' => 'Untitled Task',
    // Add keys for main.js if needed (e.g., for showMessage types)
    'js_message_type_success' => 'Success',
    'js_message_type_error' => 'Error',
    'js_message_type_info' => 'Info',

    // Footer
    'footer_copyright' => 'Copyright © %d Adrian. All rights reserved.', // %d for year

    // JavaScript Translations (main.js)
    'js_logging_in' => 'Logging in...',
    'js_login_failed_default' => 'Login failed.',
    'js_login_failed_error' => 'Login failed: %s. Please try again.', // %s for error message
    'js_registering' => 'Registering...',
    'js_passwords_no_match' => 'Passwords do not match.',
    'js_password_too_short' => 'Password must be at least 6 characters long.',
    'js_registration_failed_default' => 'Registration failed.',
    'js_registration_failed_error' => 'Registration failed: %s. Please try again.', // %s for error message

];
