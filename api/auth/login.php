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
        // --- Find User in Database ---
        try {
            // --- SIMPLIFIED QUERY FOR DEBUGGING ---
            // Prepare SQL using only username for now
            $sql = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
            $stmt = $pdo->prepare($sql);

            // Execute by passing an indexed array with the value for the placeholder
            $params = [$username_or_email];
            $stmt->execute($params);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // If user not found by username, we could try searching by email separately later as a workaround
            // if (!$user) { ... try email query ... }

            if ($user) {
                // --- Verify Password ---
                if (password_verify($password, $user['password'])) {
                    // Password is correct - Login successful
                    $response['success'] = true;
                    $response['message'] = 'Login successful!';

                    // --- Store user info in session ---
                    // Regenerate session ID for security upon login
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    // Store other relevant info if needed, but avoid storing sensitive data

                } else {
                    // Invalid password
                    $response['message'] = 'Invalid username/email or password.';
                }
            } else {
                // User not found
                $response['message'] = 'Invalid username/email or password.';
            }

        } catch (PDOException $e) {
            $response['message'] = 'Database error during login.';
            // Log the detailed error message internally
            error_log("PDOException in login.php: " . $e->getMessage());
            // Add the specific SQL error code to the response for debugging
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
