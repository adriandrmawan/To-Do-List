<?php
// api/auth/login.php

// Include necessary files
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set header to return JSON
header('Content-Type: application/json');

// Response array
$response = ['success' => false, 'message' => 'An error occurred.'];

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data
    $username_or_email = trim($_POST['username'] ?? ''); // Can be username or email
    $password = $_POST['password'] ?? '';

    // --- Basic Validation ---
    if (empty($username_or_email) || empty($password)) {
        $response['message'] = 'Please enter both username/email and password.';
    } else {
        // --- Find User in Database (Two-Step Check) ---
        try {
            $user = null; // Initialize user variable

            // 1. Try finding user by username
            $sql_user = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
            $stmt_user = $pdo->prepare($sql_user);
            $stmt_user->execute([$username_or_email]);
            $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

            // 2. If not found by username, try finding by email
            if (!$user) {
                $sql_email = "SELECT id, username, password FROM users WHERE email = ? LIMIT 1";
                $stmt_email = $pdo->prepare($sql_email);
                $stmt_email->execute([$username_or_email]);
                $user = $stmt_email->fetch(PDO::FETCH_ASSOC);
            }

            // 3. Check if user was found by either method
            if ($user) {
                // --- Verify Password ---
                if (password_verify($password, $user['password'])) {
                    // Password is correct - Login successful
                    $response['success'] = true;
                    $response['message'] = 'Login successful!';

                    // --- Store user info in session ---
                    session_regenerate_id(true); // Regenerate session ID
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                } else {
                    // Invalid password
                    $response['message'] = 'Invalid username/email or password.';
                }
            } else {
                // User not found by username or email
                $response['message'] = 'Invalid username/email or password.';
            }

        } catch (PDOException $e) {
            $response['message'] = 'Database error during login.';
            error_log("PDOException in login.php: " . $e->getMessage());
            $response['sql_error_code'] = $e->getCode();
        } catch (Exception $e) {
             $response['message'] = 'An unexpected error occurred during login.';
             error_log("Exception in login.php: " . $e->getMessage());
        }
    }
} else {
    // Not a POST request
    $response['message'] = 'Invalid request method.';
}

// Send JSON response back to the frontend
echo json_encode($response);
exit;
?>
