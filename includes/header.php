<?php
// Start the session on all pages that include this header
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List App</title> <!-- Default title, can be overridden -->

    <!-- Favicon (Optional - Add your own favicon links here) -->
    <!-- <link rel="icon" href="assets/img/favicon.ico"> -->

    <!-- Google Fonts or System Fonts (SF Pro/system-ui as per spec) -->
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"; }
    </style>

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Animations Stylesheet -->
    <link rel="stylesheet" href="assets/css/animations.css">

    <!-- FontAwesome (Example using CDN - replace if downloaded) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Add other head elements like meta tags if needed -->
</head>
<body>
    <!-- Main application container or wrapper can start here if needed -->
    <!-- Example: <div id="app"> -->

    <!-- Navigation bar or header content specific to pages will go after including this file -->
