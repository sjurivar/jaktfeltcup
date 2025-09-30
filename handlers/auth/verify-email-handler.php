<?php
/**
 * Email Verification Handler
 */

// Start session
session_start();

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Services/EmailService.php';

// Get verification code from form
$verification_code = trim($_POST['verification_code'] ?? '');

// Validate input
if (empty($verification_code)) {
    $_SESSION['error'] = 'Bekreftelseskode er påkrevd';
    header('Location: /verify-email');
    exit;
}

// Initialize email service with Mailjet
$emailService = new EmailService($app_config, $database, $mailjet_config);

// Verify the code
$verification = $emailService->verifyEmailCode($verification_code);

if ($verification) {
    // Get user information
    $user = $database->queryOne(
        "SELECT id, username, first_name, last_name, email_verified FROM jaktfelt_users WHERE id = ?",
        [$verification['user_id']]
    );
    
    if ($user) {
        // Set user session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['email_verified'] = true;
        
        $_SESSION['success'] = 'E-postadresse bekreftet! Velkommen til Jaktfeltcup.';
        header('Location: /participant/dashboard');
    } else {
        $_SESSION['error'] = 'Bruker ikke funnet. Kontakt administrator.';
        header('Location: /verify-email');
    }
} else {
    $_SESSION['error'] = 'Ugyldig eller utløpt bekreftelseskode. Prøv igjen.';
    header('Location: /verify-email');
}

exit;
