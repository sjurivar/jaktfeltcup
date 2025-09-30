<?php
/**
 * Logout Handler
 * 
 * Handles user logout and redirects to landing page
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Clear all session data
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to landing page with success message
header('Location: ' . base_url() . '?logout=success');
exit;
?>
