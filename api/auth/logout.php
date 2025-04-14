<?php
// api/auth/logout.php

require_once __DIR__ . '/../../includes/functions.php'; // For redirect function

// Ensure session is started before attempting to destroy it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Unset all session variables
$_SESSION = array();

// 2. Delete the session cookie (optional but recommended)
// This will delete the session cookie, not just the session data.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destroy the session.
session_destroy();

// 4. Redirect to the login page
// Determine the correct relative path from api/auth/ to login.php
// Since logout.php is in api/auth/, we need to go up two levels to reach the root where login.php is.
// However, the redirect function in functions.php simply uses the provided URL.
// We need a path relative to the web root to ensure it goes to the correct index.php.
redirect('/to-do/index.php'); // Path relative to web root (localhost)

exit; // Ensure no further code execution
?>
