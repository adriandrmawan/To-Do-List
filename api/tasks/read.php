<?php
// api/tasks/read.php

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// Ensure session is started and user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'An error occurred.', 'tasks' => []];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Authentication required.';
    http_response_code(401); // Unauthorized
    echo json_encode($response);
    exit;
}

// Only allow GET requests for reading tasks
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response['message'] = 'Invalid request method. Only GET is allowed.';
    http_response_code(405); // Method Not Allowed
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];

// --- Fetch Tasks from Database ---
try {
    // Base SQL query - fetch all columns for the logged-in user
    // Order by creation date descending by default
    $sql = "SELECT id, title, description, status, priority, created_at, due_date
            FROM tasks
            WHERE user_id = :user_id
            ORDER BY created_at DESC"; // Example ordering

    // Add filtering/sorting based on GET parameters later if needed
    // e.g., ?status=pending&sort=priority_high

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response['success'] = true;
    $response['message'] = 'Tasks fetched successfully.';
    $response['tasks'] = $tasks;
    http_response_code(200); // OK

} catch (PDOException $e) {
    $response['message'] = 'Database error while fetching tasks.';
    error_log("PDOException in read.php: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
} catch (Exception $e) {
     $response['message'] = 'An unexpected error occurred while fetching tasks.';
     error_log("Exception in read.php: " . $e->getMessage());
     http_response_code(500); // Internal Server Error
}

// Send JSON response
echo json_encode($response);
exit;
?>
