<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'To-Do App'; ?></title>
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
                <a href="<?php echo isset($_SESSION['user_id']) ? 'dashboard.php' : 'index.php'; ?>" class="logo">To-Do App</a>
            </div>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Authenticated user navigation -->
                <div class="header-right">
                    <span class="welcome-text">Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>!</span>
                    <a href="api/auth/logout.php" class="logout-link">Logout</a>
                </div>
            <?php else: ?>
                <!-- Public navigation -->
                <div class="public-nav-links">
                    <a href="login.php">Sign In</a>
                    <a href="register.php" class="button button-primary button-small">Sign Up</a>
                </div>
            <?php endif; ?>
        </nav>
    </header>
</body>
</html>
