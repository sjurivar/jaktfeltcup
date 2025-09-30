<?php
/**
 * Resend Verification Code Handler
 */

// Start session
session_start();

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Services/EmailService.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Ikke innlogget']);
    exit;
}

$userId = $_SESSION['user_id'];

// Get user information
$user = $database->queryOne(
    "SELECT id, email, first_name, email_verified FROM jaktfelt_users WHERE id = ?",
    [$userId]
);

if (!$user) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Bruker ikke funnet']);
    exit;
}

// Check if already verified
if ($user['email_verified']) {
    echo json_encode(['success' => false, 'message' => 'E-postadresse allerede bekreftet']);
    exit;
}

// Initialize email service with Mailjet
$emailService = new EmailService($app_config, $database, $mailjet_config);

// Send new verification code
$emailSent = $emailService->sendVerificationCode($user['id'], $user['email'], $user['first_name']);

if ($emailSent) {
    echo json_encode(['success' => true, 'message' => 'Bekreftelseskode sendt på nytt']);
} else {
    echo json_encode(['success' => false, 'message' => 'Kunne ikke sende e-post']);
}
