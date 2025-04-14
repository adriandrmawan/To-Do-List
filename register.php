<?php
require_once 'includes/functions.php';

// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

// Include header
$pageTitle = "Sign Up - ToDo App"; // Optional: Set a specific page title
require_once 'includes/header_public.php'; // Use public header
?>

<div class="auth-container"> <!-- Wrapper for centering -->
    <div class="auth-card"> <!-- Card styling -->
        <h1>Create Account</h1>

        <div id="register-message" class="message" style="display: none;"></div> <!-- Message area inside card -->

        <form id="register-form" class="auth-form" action="api/auth/register.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <!-- Add CSRF token field later -->
            <button type="submit" class="button button-primary button-full-width">Sign Up</button>
        </form>

        <p class="auth-switch-link">Already have an account? <a href="login.php">Sign In</a>.</p>
    </div>
</div> <!-- /auth-container -->

<?php
// Include footer
require_once 'includes/footer_public.php'; // Use public footer
?>
