<?php
/**
 * Test Mailjet email functionality
 * Run this script to test Mailjet email sending
 */

// Include configuration
require_once __DIR__ . '/config/config.php';

// Include Mailjet service
require_once __DIR__ . '/src/Services/EmailServiceMailjet.php';

use Jaktfeltcup\Services\EmailServiceMailjet;

echo "<h1>Mailjet E-post Test</h1>\n";
echo "<hr>\n";

// Initialize Mailjet service
$emailService = new EmailServiceMailjet($app_config, null, $mailjet_config);

// Test 1: Test connection
echo "<h2>1. Test Mailjet API tilkobling</h2>\n";
$connection_test = $emailService->testConnection();

if ($connection_test['success']) {
    echo "<p style='color: green;'>✅ " . $connection_test['message'] . "</p>\n";
} else {
    echo "<p style='color: red;'>❌ " . $connection_test['message'] . "</p>\n";
    echo "<p><strong>Tips:</strong> Sjekk at Mailjet API nøkler er riktig konfigurert i config.php</p>\n";
}

echo "<hr>\n";

// Test 2: Send test email
echo "<h2>2. Send test e-post</h2>\n";

$test_email = 'sjurivar@gmail.com'; // Change to your email
$subject = 'Test e-post fra Jaktfeltcup';
$message = '
<html>
<head>
    <title>Test e-post</title>
</head>
<body>
    <h2>Test e-post fra Jaktfeltcup</h2>
    <p>Dette er en test e-post sendt via Mailjet API.</p>
    <p><strong>Dato:</strong> ' . date('Y-m-d H:i:s') . '</p>
    <p><strong>Fra:</strong> Jaktfeltcup</p>
    <hr>
    <p><em>Dette er en automatisk generert test e-post.</em></p>
</body>
</html>';

echo "<p><strong>Sender til:</strong> " . htmlspecialchars($test_email) . "</p>\n";
echo "<p><strong>Emne:</strong> " . htmlspecialchars($subject) . "</p>\n";

$result = $emailService->sendEmail($test_email, $subject, $message);

if ($result['success']) {
    echo "<p style='color: green;'>✅ " . $result['message'] . "</p>\n";
    if (isset($result['mailjet_id'])) {
        echo "<p><strong>Mailjet ID:</strong> " . $result['mailjet_id'] . "</p>\n";
    }
} else {
    echo "<p style='color: red;'>❌ " . $result['message'] . "</p>\n";
}

echo "<hr>\n";

// Test 3: Configuration check
echo "<h2>3. Konfigurasjonssjekk</h2>\n";

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

// Test 4: Instructions
echo "<h2>4. Instruksjoner for Mailjet setup</h2>\n";
echo "<ol>\n";
echo "<li><strong>Opprett Mailjet konto:</strong> Gå til <a href='https://www.mailjet.com' target='_blank'>mailjet.com</a></li>\n";
echo "<li><strong>Få API nøkler:</strong> Gå til Account Settings → API Key Management</li>\n";
echo "<li><strong>Oppdater config.php:</strong> Sett inn dine API nøkler i \$mailjet_config</li>\n";
echo "<li><strong>Verifiser domene:</strong> Legg til jaktfeltcup.no i Mailjet dashboard</li>\n";
echo "<li><strong>Test sending:</strong> Kjør denne testen igjen</li>\n";
echo "</ol>\n";

echo "<hr>\n";
echo "<p><strong>Test fullført:</strong> " . date('Y-m-d H:i:s') . "</p>\n";
?>
