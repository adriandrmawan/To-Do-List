<?php

// Reusable PHP Functions for the To-Do List App

/**
 * Checks if a user is currently logged in.
 * Redirects to the login page if not logged in (optional).
 *
 * @param bool $redirect If true, redirects to login page if not logged in.
 * @return bool True if logged in, false otherwise.
 */
function isLoggedIn(bool $redirect = false): bool {
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Ensure session is started
    }

    $loggedIn = isset($_SESSION['user_id']);

    if (!$loggedIn && $redirect) {
        // Store the intended destination to redirect back after login (optional)
        // $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

        header('Location: login.php'); // Adjust path if necessary
        exit;
    }

    return $loggedIn;
}

/**
 * Escapes HTML special characters for output to prevent XSS.
 *
 * @param string|null $string The string to escape.
 * @return string The escaped string.
 */
function escape(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirects the user to a specified URL.
 *
 * @param string $url The URL to redirect to.
 */
function redirect(string $url): void {
    header("Location: " . $url);
    exit;
}

// Add more helper functions here as needed, e.g.:
// - validateInput()
// - flashMessage()
// - generateCsrfToken() / verifyCsrfToken()

?>
