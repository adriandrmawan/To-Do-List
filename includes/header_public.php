<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'ToDo App'; ?></title>
    <!-- Link to existing CSS file -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    <!-- Add FontAwesome if needed for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 
    <style>
        /* Inline critical font style for faster perceived load */
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'San Francisco', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; 
        }
    </style>
</head>
<body>
    <!-- Public Header -->
    <header class="public-header">
        <nav class="container">
            <a href="index.php" class="logo">To-Do App</a> 
            <div class="public-nav-links">
                <a href="login.php">Sign In</a>
                <a href="register.php" class="button button-primary button-small">Sign Up</a> 
            </div>
        </nav>
    </header>
