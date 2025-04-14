<?php
// api/tasks/update.php

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

// Check if it's a POST request (using POST for simplicity, could use PUT/PATCH)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // Get input data from POST body (assuming JSON payload)
    $input = json_decode(file_get_contents('php://input'), true);

    // --- Basic Validation ---
    $task_id = filter_var($input['task_id'] ?? null, FILTER_VALIDATE_INT);

    if ($task_id === false || $task_id === null) {
        $response['message'] = 'Invalid or missing Task ID.';
        http_response_code(400); // Bad Request
        echo json_encode($response);
        exit;
    }

    // --- Verify Task Ownership ---
    try {
        $sql_check = "SELECT id FROM tasks WHERE id = :task_id AND user_id = :user_id LIMIT 1";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_check->execute();
        $task_exists = $stmt_check->fetch();

        if (!$task_exists) {
            $response['message'] = 'Task not found or you do not have permission to update it.';
            http_response_code(404); // Not Found or 403 Forbidden
            echo json_encode($response);
            exit;
        }

        // --- Prepare Update ---
        $fields_to_update = [];
        $params = [':task_id' => $task_id, ':user_id' => $user_id]; // Include IDs for safety

        // Check each possible field from input
        if (isset($input['title'])) {
            $title = trim($input['title']);
            if (!empty($title)) {
                $fields_to_update[] = "title = :title";
                $params[':title'] = $title;
            } else {
                 $response['message'] = 'Title cannot be empty if provided.'; http_response_code(400); echo json_encode($response); exit;
            }
        }
        if (isset($input['description'])) {
            $fields_to_update[] = "description = :description";
            $params[':description'] = trim($input['description']);
        }
        if (isset($input['priority'])) {
            $priority = $input['priority'];
            $allowed_priorities = ['low', 'medium', 'high'];
            if (in_array($priority, $allowed_priorities)) {
                $fields_to_update[] = "priority = :priority";
                $params[':priority'] = $priority;
            } // Ignore invalid priority or return error
        }
        if (isset($input['status'])) {
            $status = $input['status'];
            $allowed_statuses = ['pending', 'completed'];
            if (in_array($status, $allowed_statuses)) {
                $fields_to_update[] = "status = :status";
                $params[':status'] = $status;
            } // Ignore invalid status or return error
        }
        if (array_key_exists('due_date', $input)) { // Use array_key_exists to allow setting to null
            $due_date = $input['due_date'];
            if ($due_date === null || $due_date === '' || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $due_date)) {
                 $fields_to_update[] = "due_date = :due_date";
                 $params[':due_date'] = ($due_date === '' || $due_date === null) ? null : $due_date;
            } // Ignore invalid date format or return error
        }

        // Check if there's anything to update
        if (empty($fields_to_update)) {
            $response['message'] = 'No valid fields provided for update.';
            http_response_code(400);
            echo json_encode($response);
            exit;
        }

        // --- Execute Update ---
        $sql_update = "UPDATE tasks SET " . implode(', ', $fields_to_update) . " WHERE id = :task_id AND user_id = :user_id";
        $stmt_update = $pdo->prepare($sql_update);

        if ($stmt_update->execute($params)) {
            if ($stmt_update->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Task updated successfully.';
                http_response_code(200); // OK
            } else {
                 // Query executed but no rows affected (might happen if data is the same)
                 $response['success'] = true; // Still considered success
                 $response['message'] = 'Task update processed, but no changes were detected.';
                 http_response_code(200); // OK
            }
        } else {
            $response['message'] = 'Failed to update task.';
            error_log("Task update failed for Task ID: " . $task_id . ", User ID: " . $user_id . " - Error: " . implode(":", $stmt_update->errorInfo()));
            http_response_code(500); // Internal Server Error
        }

    } catch (PDOException $e) {
        $response['message'] = 'Database error during task update.';
        error_log("PDOException in update.php: " . $e->getMessage());
        http_response_code(500); // Internal Server Error
    } catch (Exception $e) {
         $response['message'] = 'An unexpected error occurred during task update.';
         error_log("Exception in update.php: " . $e->getMessage());
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
