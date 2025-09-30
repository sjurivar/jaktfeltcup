<?php
/**
 * Enkel Mailjet test uten database
 */

// Include configuration
require_once __DIR__ . '/config/config.php';

// Include Mailjet service
require_once __DIR__ . '/src/Services/EmailServiceMailjet.php';

use Jaktfeltcup\Services\EmailServiceMailjet;

echo "=== Mailjet Enkel Test ===\n\n";

// Initialize Mailjet service
$emailService = new EmailServiceMailjet($app_config, null, $mailjet_config);

// Test connection
echo "1. Tester Mailjet API tilkobling...\n";
$connection_test = $emailService->testConnection();

if ($connection_test['success']) {
    echo "✅ " . $connection_test['message'] . "\n";
} else {
    echo "❌ " . $connection_test['message'] . "\n";
    echo "Tips: Sjekk API nøkler i config.php\n";
    exit;
}

// Send test email
echo "\n2. Sender test e-post...\n";

$test_email = 'test@example.com'; // Change to your email
$subject = 'Test fra Jaktfeltcup';
$message = '<h2>Test e-post</h2><p>Dette er en test e-post sendt via Mailjet.</p>';

$result = $emailService->sendEmail($test_email, $subject, $message);

if ($result['success']) {
    echo "✅ " . $result['message'] . "\n";
    if (isset($result['mailjet_id'])) {
        echo "Mailjet ID: " . $result['mailjet_id'] . "\n";
    }
} else {
    echo "❌ " . $result['message'] . "\n";
}

echo "\n=== Test fullført ===\n";
?>
