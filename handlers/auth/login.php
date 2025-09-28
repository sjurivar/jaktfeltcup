<?php
/**
 * Login Handler
 */

// Start session
session_start();

// Get form data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Validate input
if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Vennligst fyll ut alle felt';
    header('Location: ' . base_url('login'));
    exit;
}

// Check user credentials
$user = $database->queryOne(
    "SELECT * FROM jaktfelt_users WHERE email = ? AND is_active = 1",
    [$email]
);

if (!$user || !password_verify($password, $user['password_hash'])) {
    $_SESSION['error'] = 'Ugyldig e-post eller passord';
    header('Location: ' . base_url('login'));
    exit;
}

// Check if email is verified
if (!$user['email_verified']) {
    $_SESSION['error'] = 'E-postadresse ikke bekreftet. Sjekk din e-post for bekreftelseskode.';
    $_SESSION['user_id'] = $user['id']; // Set user_id for resend functionality
    header('Location: ' . base_url('verify-email'));
    exit;
}

// Login successful
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

// Get user roles from new role system
$user_roles = [];
try {
    $user_roles = $database->queryAll(
        "SELECT r.role_name FROM jaktfelt_user_roles ur 
         JOIN jaktfelt_roles r ON ur.role_id = r.id 
         WHERE ur.user_id = ?", 
        [$user['id']]
    );
    $user_roles = array_column($user_roles, 'role_name');
} catch (Exception $e) {
    // If roles table doesn't exist, fall back to old role system
    error_log("Could not fetch user roles: " . $e->getMessage());
    $user_roles = [$user['role']];
}

// Redirect based on roles
if (in_array('admin', $user_roles)) {
    header('Location: ' . base_url('admin'));
} elseif (in_array('databasemanager', $user_roles) || in_array('contentmanager', $user_roles) || in_array('rolemanager', $user_roles)) {
    header('Location: ' . base_url('admin'));
} elseif (in_array('organizer', $user_roles) || $user['role'] === 'organizer') {
    header('Location: ' . base_url('organizer/dashboard'));
} else {
    // Default to participant dashboard
    header('Location: ' . base_url('participant/dashboard'));
}
exit;
