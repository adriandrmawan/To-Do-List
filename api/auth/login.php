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
            // Prepare SQL to find user by username OR email
            $sql = "SELECT id, username, password FROM users WHERE username = :identifier OR email = :identifier LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':identifier', $username_or_email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

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

                    // Optional: Redirect URL after login (can be sent back to JS)
                    // $response['redirect'] = 'dashboard.php';

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
