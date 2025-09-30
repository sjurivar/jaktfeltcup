<?php
/**
 * Test SMTP directly with your configuration
 */

// Load configuration
require_once __DIR__ . '/config/config.php';

echo "<h2>SMTP Direct Test</h2>";

// Test 1: Check configuration
echo "<h3>Test 1: Konfigurasjon</h3>";
echo "<pre>";
print_r($mail_config);
echo "</pre>";

// Test 2: Basic mail with your SMTP settings
echo "<h3>Test 2: Basic Mail with SMTP Settings</h3>";

// Set SMTP settings in php.ini context
ini_set('SMTP', $mail_config['host']);
ini_set('smtp_port', $mail_config['port']);
ini_set('sendmail_from', $mail_config['from_address']);

$to = "test@example.com";
$subject = "Test e-post fra Nasjonal 15m Jaktfeltcup (SMTP)";
$message = "
<html>
<head><title>Test E-post</title></head>
<body>
    <h2>Test e-post fra Nasjonal 15m Jaktfeltcup</h2>
    <p>Dette er en test e-post sendt via SMTP.</p>
    <p>Sendt: " . date('Y-m-d H:i:s') . "</p>
</body>
</html>";

$headers = "From: " . $mail_config['from_address'] . "\r\n";
$headers .= "Reply-To: " . $mail_config['from_address'] . "\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

echo "<p><strong>SMTP Host:</strong> " . $mail_config['host'] . "</p>";
echo "<p><strong>SMTP Port:</strong> " . $mail_config['port'] . "</p>";
echo "<p><strong>From:</strong> " . $mail_config['from_address'] . "</p>";

if (mail($to, $subject, $message, $headers)) {
    echo "<p style='color: green; font-weight: bold;'>✅ SMTP mail sent successfully!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ SMTP mail failed</p>";
    echo "<p>Last error: " . (error_get_last()['message'] ?? 'Unknown error') . "</p>";
}

// Test 3: Interactive test
echo "<h3>Test 3: Send til din e-post</h3>";
echo "<form method='post'>";
echo "<p><label>Din e-post adresse: <input type='email' name='user_email' placeholder='din@epost.no' required></label></p>";
echo "<p><button type='submit' name='send_test'>Send Test E-post</button></p>";
echo "</form>";

if (isset($_POST['send_test']) && !empty($_POST['user_email'])) {
    $user_email = $_POST['user_email'];
    
    $subject = "Test e-post fra Nasjonal 15m Jaktfeltcup (SMTP)";
    $message = "
    <html>
    <head><title>Test E-post</title></head>
    <body>
        <h2>Test e-post fra Nasjonal 15m Jaktfeltcup</h2>
        <p>Hei!</p>
        <p>Dette er en test e-post sendt via SMTP.</p>
        <p>Hvis du mottar denne e-posten, betyr det at SMTP fungerer!</p>
        <p>Sendt: " . date('Y-m-d H:i:s') . "</p>
    </body>
    </html>";
    
    $headers = "From: " . $mail_config['from_address'] . "\r\n";
    $headers .= "Reply-To: " . $mail_config['from_address'] . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
    if (mail($user_email, $subject, $message, $headers)) {
        echo "<p style='color: green; font-weight: bold;'>✅ Test e-post sendt til $user_email!</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Kunne ikke sende e-post til $user_email</p>";
        echo "<p>Last error: " . (error_get_last()['message'] ?? 'Unknown error') . "</p>";
    }
}

// Test 4: System check
echo "<h3>Test 4: System Check</h3>";

if (function_exists('mail')) {
    echo "<p style='color: green;'>✅ PHP mail() function is available</p>";
} else {
    echo "<p style='color: red;'>❌ PHP mail() function is NOT available</p>";
}

echo "<p><strong>Current SMTP settings:</strong></p>";
echo "<ul>";
echo "<li>SMTP: " . ini_get('SMTP') . "</li>";
echo "<li>SMTP Port: " . ini_get('smtp_port') . "</li>";
echo "<li>Sendmail From: " . ini_get('sendmail_from') . "</li>";
echo "</ul>";

echo "<h3>Instruksjoner:</h3>";
echo "<ol>";
echo "<li>Test med formen over for å sende til din e-post</li>";
echo "<li>Sjekk at e-posten kommer frem</li>";
echo "<li>Hvis det ikke fungerer, sjekk at SMTP-serveren er tilgjengelig</li>";
echo "<li>Kontakt din hosting-leverandør for SMTP-innstillinger</li>";
echo "</ol>";
?>
