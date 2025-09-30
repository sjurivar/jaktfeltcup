<?php
/**
 * Test Mailjet integration in the application
 * This tests the actual EmailService with Mailjet
 */

// Include configuration
require_once __DIR__ . '/config/config.php';

// Include EmailService
require_once __DIR__ . '/src/Services/EmailService.php';

use Jaktfeltcup\Services\EmailService;

echo "<h1>Mailjet Integration Test</h1>\n";
echo "<hr>\n";

// Initialize EmailService with Mailjet
$emailService = new EmailService($app_config, $database, $mailjet_config);

// Test 1: Send verification email
echo "<h2>1. Test e-post verifisering</h2>\n";

$test_user_id = 1; // Use existing user ID
$test_email = 'sjurivar@gmail.com'; // Your email
$test_first_name = 'Test Bruker';

echo "<p><strong>Sender verifiserings-e-post til:</strong> " . htmlspecialchars($test_email) . "</p>\n";

$result = $emailService->sendVerificationCode($test_user_id, $test_email, $test_first_name);

if ($result) {
    echo "<p style='color: green;'>✅ Verifiserings-e-post sendt via Mailjet</p>\n";
} else {
    echo "<p style='color: red;'>❌ Kunne ikke sende verifiserings-e-post</p>\n";
}

echo "<hr>\n";

// Test 2: Send password reset email
echo "<h2>2. Test passord tilbakestilling</h2>\n";

$test_reset_token = bin2hex(random_bytes(16));

echo "<p><strong>Sender passord tilbakestilling til:</strong> " . htmlspecialchars($test_email) . "</p>\n";

$result = $emailService->sendPasswordResetEmail($test_user_id, $test_email, $test_first_name, $test_reset_token);

if ($result) {
    echo "<p style='color: green;'>✅ Passord tilbakestilling sendt via Mailjet</p>\n";
} else {
    echo "<p style='color: red;'>❌ Kunne ikke sende passord tilbakestilling</p>\n";
}

echo "<hr>\n";

// Test 3: Send simple email
echo "<h2>3. Test enkel e-post</h2>\n";

$subject = 'Test e-post fra Jaktfeltcup (Mailjet)';
$message = '
<html>
<head>
    <title>Test e-post</title>
</head>
<body>
    <h2>Test e-post fra Jaktfeltcup</h2>
    <p>Dette er en test e-post sendt via Mailjet integration.</p>
    <p><strong>Dato:</strong> ' . date('Y-m-d H:i:s') . '</p>
    <p><strong>Fra:</strong> Jaktfeltcup</p>
    <hr>
    <p><em>Dette er en automatisk generert test e-post.</em></p>
</body>
</html>';

echo "<p><strong>Sender enkel e-post til:</strong> " . htmlspecialchars($test_email) . "</p>\n";

$result = $emailService->sendEmail($test_email, $subject, $message);

if ($result) {
    echo "<p style='color: green;'>✅ Enkel e-post sendt via Mailjet</p>\n";
} else {
    echo "<p style='color: red;'>❌ Kunne ikke sende enkel e-post</p>\n";
}

echo "<hr>\n";

// Test 4: Configuration check
echo "<h2>4. Konfigurasjonssjekk</h2>\n";

echo "<h3>Mailjet konfigurasjon:</h3>\n";
echo "<ul>\n";
echo "<li><strong>API Key:</strong> " . (empty($mailjet_config['api_key']) ? '❌ Ikke satt' : '✅ Satt') . "</li>\n";
echo "<li><strong>Secret Key:</strong> " . (empty($mailjet_config['secret_key']) ? '❌ Ikke satt' : '✅ Satt') . "</li>\n";
echo "<li><strong>From Email:</strong> " . htmlspecialchars($mailjet_config['from_email']) . "</li>\n";
echo "<li><strong>From Name:</strong> " . htmlspecialchars($mailjet_config['from_name']) . "</li>\n";
echo "</ul>\n";

echo "<h3>App konfigurasjon:</h3>\n";
echo "<ul>\n";
echo "<li><strong>App Name:</strong> " . htmlspecialchars($app_config['name']) . "</li>\n";
echo "<li><strong>Base URL:</strong> " . htmlspecialchars($app_config['base_url']) . "</li>\n";
echo "<li><strong>Debug:</strong> " . ($app_config['debug'] ? 'På' : 'Av') . "</li>\n";
echo "</ul>\n";

echo "<hr>\n";

// Test 5: Fallback test
echo "<h2>5. Fallback test (Mailjet deaktivert)</h2>\n";

// Create EmailService without Mailjet config
$emailServiceFallback = new EmailService($app_config, $database, null);

$result = $emailServiceFallback->sendEmail($test_email, 'Fallback Test', '<p>Dette er en fallback test.</p>');

if ($result) {
    echo "<p style='color: green;'>✅ Fallback til PHP mail() fungerer</p>\n";
} else {
    echo "<p style='color: orange;'>⚠️ Fallback til PHP mail() fungerer ikke (forventet på noen servere)</p>\n";
}

echo "<hr>\n";
echo "<p><strong>Test fullført:</strong> " . date('Y-m-d H:i:s') . "</p>\n";
?>
