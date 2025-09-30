<?php
/**
 * Forgot Password Handler
 */

// Start session
session_start();

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../src/Services/EmailService.php';

// Get form data
$email = trim($_POST['email'] ?? '');

// Validate input
$errors = [];

if (empty($email)) {
    $errors[] = 'E-postadresse er påkrevd';
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Ugyldig e-postadresse';
}

// If there are errors, redirect back
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: ' . base_url('forgot-password'));
    exit;
}

// Check if user exists
$user = $database->queryOne(
    "SELECT id, username, first_name, last_name, email FROM jaktfelt_users WHERE email = ?",
    [$email]
);

if (!$user) {
    // Don't reveal if user exists or not for security
    $_SESSION['success'] = 'Hvis e-postadressen finnes i systemet, vil du motta en tilbakestillingslink.';
    header('Location: ' . base_url('forgot-password'));
    exit;
}

// Generate reset token
$reset_token = bin2hex(random_bytes(32));
$reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

// Store reset token in database
try {
    $database->execute(
        "INSERT INTO jaktfelt_password_resets (user_id, token, expires_at) VALUES (?, ?, ?)",
        [$user['id'], $reset_token, $reset_expires]
    );
    
    // Initialize email service with Mailjet
    $emailService = new EmailService($app_config, $database, $mailjet_config);
    
    // Send reset email
    $emailSent = $emailService->sendPasswordResetEmail($user['id'], $email, $user['first_name'], $reset_token);
    
    if ($emailSent) {
        $_SESSION['success'] = 'Tilbakestillingslink er sendt til din e-postadresse.';
    } else {
        $_SESSION['error'] = 'Kunne ikke sende e-post. Prøv igjen senere.';
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = 'En feil oppstod. Prøv igjen senere.';
}

header('Location: ' . base_url('forgot-password'));
exit;
