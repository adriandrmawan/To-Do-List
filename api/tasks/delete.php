<?php
// api/tasks/delete.php

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

// Check if it's a POST request (using POST for simplicity, could use DELETE)
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

    // --- Verify Task Ownership & Delete ---
    try {
        // Prepare SQL to delete the task only if it belongs to the user
        $sql = "DELETE FROM tasks WHERE id = :task_id AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Check if any row was actually deleted
            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Task deleted successfully.';
                http_response_code(200); // OK
            } else {
                // No rows deleted - task didn't exist or didn't belong to the user
                $response['message'] = 'Task not found or you do not have permission to delete it.';
                http_response_code(404); // Not Found or 403 Forbidden
            }
        } else {
            $response['message'] = 'Failed to delete task.';
            error_log("Task deletion failed for Task ID: " . $task_id . ", User ID: " . $user_id . " - Error: " . implode(":", $stmt->errorInfo()));
            http_response_code(500); // Internal Server Error
        }

    } catch (PDOException $e) {
        $response['message'] = 'Database error during task deletion.';
        error_log("PDOException in delete.php: " . $e->getMessage());
        http_response_code(500); // Internal Server Error
    } catch (Exception $e) {
         $response['message'] = 'An unexpected error occurred during task deletion.';
         error_log("Exception in delete.php: " . $e->getMessage());
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
