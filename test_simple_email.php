<?php
/**
 * Simple Email Test
 */

// Load configuration
require_once __DIR__ . '/config/config.php';

echo "<h2>Enkel E-post Test</h2>";

// Test basic mail function
$to = "test@example.com";
$subject = "Test fra Jaktfeltcup";
$message = "Dette er en test e-post.";
$headers = "From: " . $mail_config['from_address'];

echo "<p><strong>Testing basic mail() function...</strong></p>";
echo "<p>To: $to</p>";
echo "<p>From: " . $mail_config['from_address'] . "</p>";

if (mail($to, $subject, $message, $headers)) {
    echo "<p style='color: green; font-weight: bold;'>✅ E-post fungerer!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ E-post fungerer ikke</p>";
}

echo "<h3>Konfigurasjon:</h3>";
echo "<pre>";
print_r($mail_config);
echo "</pre>";

echo "<h3>For å fikse:</h3>";
echo "<ol>";
echo "<li>Sett inn riktig SMTP brukernavn og passord i config.php</li>";
echo "<li>For Gmail: Bruk App Password i stedet for vanlig passord</li>";
echo "<li>Sjekk at XAMPP har e-post støtte aktivert</li>";
echo "</ol>";
?>
