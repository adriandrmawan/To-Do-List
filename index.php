<?php
require_once 'includes/functions.php';

// Check if user is logged in
if (isLoggedIn()) {
    // If logged in, redirect to the dashboard
    redirect('dashboard.php');
} else {
    // If not logged in, redirect to the login page
    redirect('login.php');
}

// No HTML output needed here as it always redirects
?>
