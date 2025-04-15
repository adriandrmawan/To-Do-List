<?php
// Include necessary files
require_once 'includes/config.php'; // Load config first (for language)
require_once 'includes/functions.php';

// If user is already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect('dashboard.php');
}

// Set page title key for translation
$pageTitleKey = "page_title_register";
require_once 'includes/header.php'; // Use unified header
?>

<div class="auth-container"> <!-- Wrapper for centering -->
    <div class="auth-card"> <!-- Card styling -->
        <h1><?php echo t('register_heading'); ?></h1>

        <div id="register-message" class="message" style="display: none;"></div> <!-- Message area inside card -->

        <form id="register-form" class="auth-form" action="api/auth/register.php" method="POST">
            <div class="form-group">
                <label for="username"><?php echo t('register_label_username'); ?></label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email"><?php echo t('register_label_email'); ?></label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password"><?php echo t('register_label_password'); ?></label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password"><?php echo t('register_label_confirm_password'); ?></label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <!-- Add CSRF token field later -->
            <button type="submit" class="button button-primary button-full-width"><?php echo t('register_button_submit'); ?></button>
        </form>

        <p class="auth-switch-link"><?php echo t('register_switch_text'); ?> <a href="login.php"><?php echo t('register_switch_link'); ?></a>.</p>
    </div>
</div> <!-- /auth-container -->

<?php
// Include footer
require_once 'includes/footer.php'; // Use unified footer
?>
