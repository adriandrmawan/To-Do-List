<?php
// api/tasks/create.php

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Ensure session is started and user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'An error occurred.'];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Authentication required.';
    http_response_code(401); // Unauthorized
    echo json_encode($response);
    exit;
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user ID from session
    $user_id = $_SESSION['user_id'];

    // Get input data from POST body (assuming JSON payload from fetch)
    // If sending FormData, use $_POST instead
    $input = json_decode(file_get_contents('php://input'), true);

    // --- Basic Validation ---
    // Use $input['key'] if receiving JSON, or $_POST['key'] if receiving FormData
    $title = trim($input['title'] ?? ''); // Example: reading from JSON payload
    $description = trim($input['description'] ?? null);
    $priority = $input['priority'] ?? 'medium'; // Default priority
    $due_date = $input['due_date'] ?? null;

    // Validate title
    if (empty($title)) {
        $response['message'] = 'Task title is required.';
        http_response_code(400); // Bad Request
        echo json_encode($response);
        exit;
    }

    // Validate priority enum
    $allowed_priorities = ['low', 'medium', 'high'];
    if (!in_array($priority, $allowed_priorities)) {
        $priority = 'medium'; // Default if invalid
    }

    // Validate due date format (optional, basic check)
    if ($due_date !== null && !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $due_date)) {
         $due_date = null; // Set to null if format is invalid
         // Optionally, return an error instead:
         // $response['message'] = 'Invalid due date format. Use YYYY-MM-DD.';
         // http_response_code(400); echo json_encode($response); exit;
    }
    // Handle empty string for due_date as null
    if ($due_date === '') {
        $due_date = null;
    }


    // --- Insert Task into Database ---
    try {
        $sql = "INSERT INTO tasks (user_id, title, description, priority, due_date)
                VALUES (:user_id, :title, :description, :priority, :due_date)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':priority', $priority, PDO::PARAM_STR);
        $stmt->bindParam(':due_date', $due_date); // Let PDO handle null type

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Task created successfully.';
            $response['task_id'] = $pdo->lastInsertId(); // Return the ID of the new task
            http_response_code(201); // Created
        } else {
            $response['message'] = 'Failed to create task.';
            error_log("Task creation failed for user ID: " . $user_id . " - Error: " . implode(":", $stmt->errorInfo()));
            http_response_code(500); // Internal Server Error
        }

    } catch (PDOException $e) {
        $response['message'] = 'Database error during task creation.';
        error_log("PDOException in create.php: " . $e->getMessage());
        http_response_code(500); // Internal Server Error
    } catch (Exception $e) {
         $response['message'] = 'An unexpected error occurred during task creation.';
         error_log("Exception in create.php: " . $e->getMessage());
         http_response_code(500); // Internal Server Error
    }

} else {
    // Not a POST request
    $response['message'] = 'Invalid request method.';
    http_response_code(405); // Method Not Allowed
}

// Send JSON response
echo json_encode($response);
exit;
?>
