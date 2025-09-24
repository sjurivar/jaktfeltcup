<?php
/**
 * Login Handler
 */

// Start session
session_start();

// Get form data
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validate input
if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'Vennligst fyll ut alle felt';
    header('Location: /login');
    exit;
}

// Check user credentials
$user = $database->queryOne(
    "SELECT * FROM jaktfelt_users WHERE email = ? AND is_active = 1",
    [$email]
);

if (!$user || !password_verify($password, $user['password_hash'])) {
    $_SESSION['error'] = 'Ugyldig e-post eller passord';
    header('Location: /login');
    exit;
}

// Check if email is verified
if (!$user['email_verified']) {
    $_SESSION['error'] = 'E-postadresse ikke bekreftet. Sjekk din e-post for bekreftelseskode.';
    $_SESSION['user_id'] = $user['id']; // Set user_id for resend functionality
    header('Location: /verify-email');
    exit;
}

// Login successful
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];
$_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

// Redirect based on role
switch ($user['role']) {
    case 'admin':
        header('Location: /admin/dashboard');
        break;
    case 'organizer':
        header('Location: /organizer/dashboard');
        break;
    case 'participant':
    default:
        header('Location: /participant/dashboard');
        break;
}
exit;
