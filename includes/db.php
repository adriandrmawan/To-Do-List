<?php

// Include the configuration file
require_once __DIR__ . '/config.php';

// PDO Data Source Name (DSN)
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

// PDO Options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Turn on errors in the form of exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Make the default fetch be an associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Turn off emulation mode for real prepared statements
];

// Database Connection Variable
$pdo = null;

try {
    // Attempt to create the PDO connection
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    // Handle connection error
    // In a production environment, you might log this error instead of displaying it
    // For development, displaying the error is often helpful
    error_log("Database Connection Error: " . $e->getMessage()); // Log error
    die("Database connection failed. Please check configuration or ensure the database server is running. Error: " . $e->getMessage()); // Stop script execution
}

// The $pdo variable now holds the database connection object if successful
// You can include this file (db.php) in other PHP scripts to get access to the $pdo object.

?>
