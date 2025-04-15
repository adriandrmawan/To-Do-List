<?php
// Ensure config (which loads translations) and functions are loaded
// Note: This assumes config.php and functions.php are included *before* this header
// in the main page files (e.g., index.php, dashboard.php).
// If not, they should be required here:
// require_once __DIR__ . '/config.php';
// require_once __DIR__ . '/functions.php';

// Start session if not already started (config.php already does this, but safe to keep)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get current language from config.php (ensure it's loaded before!)
global $current_lang; // Make sure $current_lang is accessible

// Determine page title using translation
$defaultTitle = t('page_title_default');
$translatedTitle = isset($pageTitleKey) ? t($pageTitleKey) : (isset($pageTitle) ? htmlspecialchars($pageTitle) : $defaultTitle); // Allow setting a key or direct title

?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang ?? 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translatedTitle; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'San Francisco', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
    </style>
</head>
<body>
    <header class="<?php echo isset($_SESSION['user_id']) ? 'dashboard-header' : 'public-header'; ?>">
        <nav class="container">
            <div class="header-left">
                <a href="<?php echo isset($_SESSION['user_id']) ? 'dashboard.php' : 'index.php'; ?>" class="logo"><?php echo t('app_name'); ?></a>
            </div>

            <div class="header-right">
                <?php
                    // Determine current page path for language switcher links
                    $current_page_uri = strtok($_SERVER["REQUEST_URI"], '?');
                    $query_string = http_build_query(array_merge($_GET, ['lang' => ''])); // Preserve other GET params
                ?>
                <div class="lang-switcher">
                    <a href="<?php echo $current_page_uri . '?' . http_build_query(array_merge($_GET, ['lang' => 'en'])); ?>" class="<?php echo ($current_lang === 'en') ? 'active' : ''; ?>">EN</a>
                    <span>|</span>
                    <a href="<?php echo $current_page_uri . '?' . http_build_query(array_merge($_GET, ['lang' => 'id'])); ?>" class="<?php echo ($current_lang === 'id') ? 'active' : ''; ?>">ID</a>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Authenticated user navigation -->
                    <span class="welcome-text"><?php echo t('welcome_message', escape($_SESSION['username'] ?? 'User')); ?></span>
                    <a href="api/auth/logout.php" class="logout-link"><?php echo t('logout_link'); ?></a>
                <?php else: ?>
                    <!-- Public navigation -->
                    <div class="public-nav-links">
                        <a href="login.php"><?php echo t('login_link'); ?></a>
                        <a href="register.php" class="button button-primary button-small"><?php echo t('register_button'); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </header>
</body>
</html>
