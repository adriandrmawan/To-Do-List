<?php
require_once 'includes/functions.php';

// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

// Include header
$pageTitle = "Login"; // Optional: Set a specific page title
require_once 'includes/header.php';
?>

<div class="container"> <!-- Example container -->
    <h1>Login</h1>

    <!-- Login Form (to be implemented) -->
    <form id="login-form" action="api/auth/login.php" method="POST">
        <div class="form-group">
            <label for="username">Username or Email:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <!-- Add CSRF token field later -->
        <button type="submit">Login</button>
        <div id="login-message"></div> <!-- For displaying errors/success -->
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a>.</p>

</div> <!-- /container -->

<?php
// Include footer
require_once 'includes/footer.php';
?>
