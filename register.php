<?php
require_once 'includes/functions.php';

// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

// Include header
$pageTitle = "Register"; // Optional: Set a specific page title
require_once 'includes/header.php';
?>

<div class="container"> <!-- Example container -->
    <h1>Register</h1>

    <!-- Registration Form (to be implemented) -->
    <form id="register-form" action="api/auth/register.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <!-- Add CSRF token field later -->
        <button type="submit">Register</button>
        <div id="register-message"></div> <!-- For displaying errors/success -->
    </form>

    <p>Already have an account? <a href="login.php">Login here</a>.</p>

</div> <!-- /container -->

<?php
// Include footer
require_once 'includes/footer.php';
?>
