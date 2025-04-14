<?php
// api/auth/register.php

// Include necessary files
require_once __DIR__ . '/../../includes/config.php'; // Go up two directories to includes
require_once __DIR__ . '/../../includes/db.php';     // Go up two directories to includes
require_once __DIR__ . '/../../includes/functions.php'; // Go up two directories to includes

// Set header to return JSON
header('Content-Type: application/json');

// Response array
$response = ['success' => false, 'message' => 'An error occurred.'];

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data (consider using filter_input for better security)
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // --- Basic Validation ---
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $response['message'] = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email format.';
    } elseif (strlen($password) < 6) { // Example: Minimum password length
        $response['message'] = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $response['message'] = 'Passwords do not match.';
    } else {
        // --- Check for existing user/email ---
        try {
            // Check username
            $sql_check_user = "SELECT id FROM users WHERE username = :username LIMIT 1";
            $stmt_check_user = $pdo->prepare($sql_check_user);
            $stmt_check_user->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt_check_user->execute();
            $existing_user = $stmt_check_user->fetch();

            // Check email
            $sql_check_email = "SELECT id FROM users WHERE email = :email LIMIT 1";
            $stmt_check_email = $pdo->prepare($sql_check_email);
            $stmt_check_email->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt_check_email->execute();
            $existing_email = $stmt_check_email->fetch();

            if ($existing_user) {
                $response['message'] = 'Username already taken.';
            } elseif ($existing_email) {
                $response['message'] = 'Email already registered.';
            } else {
                // --- Hash Password ---
                $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Use default cost

                if ($hashed_password === false) {
                     $response['message'] = 'Failed to hash password.';
                     // Log error internally
                     error_log("Password hashing failed for user: " . $username);
                } else {
                    // --- Insert User into Database ---
                    $sql_insert = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
                    $stmt_insert = $pdo->prepare($sql_insert);
                    $stmt_insert->bindParam(':username', $username, PDO::PARAM_STR);
                    $stmt_insert->bindParam(':email', $email, PDO::PARAM_STR);
                    $stmt_insert->bindParam(':password', $hashed_password, PDO::PARAM_STR);

                    if ($stmt_insert->execute()) {
                        $response['success'] = true;
                        $response['message'] = 'Registration successful! You can now login.';
                        // Optionally: Log the user in automatically by setting session variables
                        // $_SESSION['user_id'] = $pdo->lastInsertId();
                        // $_SESSION['username'] = $username;
                    } else {
                        $response['message'] = 'Registration failed. Please try again.';
                        // Log error internally
                        error_log("Database insert failed for user registration: " . $username . " - Error: " . implode(":", $stmt_insert->errorInfo()));
                    }
                }
            }
        } catch (PDOException $e) {
            $response['message'] = 'Database error during registration.';
            // Log the detailed error message internally, don't expose to user
            error_log("PDOException in register.php: " . $e->getMessage());
        } catch (Exception $e) {
             $response['message'] = 'An unexpected error occurred.';
             error_log("Exception in register.php: " . $e->getMessage());
        }
    }
} else {
    // Not a POST request
    $response['message'] = 'Invalid request method.';
}

// Send JSON response back to the frontend
echo json_encode($response);
exit; // Stop script execution
?>
