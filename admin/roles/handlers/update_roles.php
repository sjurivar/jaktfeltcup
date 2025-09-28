<?php
/**
 * Update User Roles Handler
 * Handles role assignment updates
 */

session_start();

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Check if user is logged in and has role management access
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('login'));
    exit;
}

// Check if user has role management access
$user_roles = [];
try {
    $user_roles = $database->queryAll(
        "SELECT r.role_name FROM jaktfelt_user_roles ur 
         JOIN jaktfelt_roles r ON ur.role_id = r.id 
         WHERE ur.user_id = ?", 
        [$_SESSION['user_id']]
    );
    $user_roles = array_column($user_roles, 'role_name');
} catch (Exception $e) {
    error_log("Could not fetch user roles: " . $e->getMessage());
}

if (!in_array('rolemanager', $user_roles) && !in_array('admin', $user_roles)) {
    $_SESSION['error'] = 'Du har ikke tilgang til rollestyring.';
    header('Location: ' . base_url('admin'));
    exit;
}

try {
    $user_id = $_POST['user_id'] ?? null;
    $selected_roles = $_POST['roles'] ?? [];
    
    if (!$user_id) {
        throw new Exception('Bruker ID mangler.');
    }
    
    // Remove all existing roles for this user
    $database->execute("DELETE FROM jaktfelt_user_roles WHERE user_id = ?", [$user_id]);
    
    // Add new roles
    foreach ($selected_roles as $role_id) {
        $database->execute(
            "INSERT INTO jaktfelt_user_roles (user_id, role_id) VALUES (?, ?)",
            [$user_id, $role_id]
        );
    }
    
    $_SESSION['success'] = 'Brukerroller oppdatert!';
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    error_log("Update roles error: " . $e->getMessage());
}

// Redirect back to roles management
header('Location: ' . base_url('admin/roles'));
exit;
