<?php
require_once 'includes/functions.php';

// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

// Include header
$pageTitle = "Sign In - ToDo App"; // Optional: Set a specific page title
require_once 'includes/header_public.php'; // Use public header
?>

<div class="auth-container"> <!-- Wrapper for centering -->
    <div class="auth-card"> <!-- Card styling -->
        <h1>Sign In</h1>

        <div id="login-message" class="message" style="display: none;"></div> <!-- Message area inside card -->

        <form id="login-form" class="auth-form" action="api/auth/login.php" method="POST">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <!-- Add CSRF token field later -->
            <button type="submit" class="button button-primary button-full-width">Sign In</button>
        </form>

        <p class="auth-switch-link">Don't have an account? <a href="register.php">Sign Up</a>.</p>
    </div>
</div> <!-- /auth-container -->

<?php
// Include footer
require_once 'includes/footer_public.php'; // Use public footer
?>
