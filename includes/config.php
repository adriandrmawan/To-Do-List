<?php

// Database Configuration Constants

// Database Host - Usually 'localhost' for XAMPP
define('DB_HOST', 'localhost');

// Database Name - As confirmed
define('DB_NAME', 'todo_app_db');

// Database User - Default XAMPP user is 'root'
define('DB_USER', 'root');

// Database Password - Default XAMPP password is empty ''
define('DB_PASS', '');

// Character Set
define('DB_CHARSET', 'utf8mb4');


// --- Language Configuration ---

// Start session if not already started (safe practice)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define available languages
$available_langs = ['en', 'id'];
$default_lang = 'en';

// Check if language is being switched via GET parameter
if (isset($_GET['lang']) && in_array($_GET['lang'], $available_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
    // Optional: Redirect to remove the query string after setting the session
    // header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
    // exit;
    // Note: Redirecting might be better UX but adds complexity. Let's skip for now.
}

// Determine the current language
$current_lang = $_SESSION['lang'] ?? $default_lang;
if (!in_array($current_lang, $available_langs)) {
    $current_lang = $default_lang; // Fallback to default if session contains invalid lang
}

// Load language file
$lang_file_path = __DIR__ . '/../lang/' . $current_lang . '.php';
$default_lang_file_path = __DIR__ . '/../lang/' . $default_lang . '.php';

// Load default language first as a fallback
$translations = [];
if (file_exists($default_lang_file_path)) {
    $translations = require $default_lang_file_path;
} else {
    // Log error or handle missing default language file
    error_log("Default language file missing: " . $default_lang_file_path);
    // Provide minimal fallback translations
    $translations = ['app_name' => 'To-Do App'];
}

// Merge/overwrite with selected language if it's not the default and exists
if ($current_lang !== $default_lang && file_exists($lang_file_path)) {
    $selected_translations = require $lang_file_path;
    if (is_array($selected_translations)) {
        // Use array_merge to keep default values if selected lang is missing keys
        $translations = array_merge($translations, $selected_translations);
    } else {
        error_log("Invalid language file format: " . $lang_file_path);
    }
} elseif ($current_lang !== $default_lang) {
    error_log("Selected language file missing: " . $lang_file_path);
}

// Make $current_lang globally accessible if needed elsewhere (optional)
// define('CURRENT_LANG', $current_lang);

?>
