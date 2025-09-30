git<?php
/**
 * Test PHP mail() function
 */

 
// Load configuration
require_once __DIR__ . '/config/config.php';

echo "<h2>PHP mail() Test</h2>";

// Test 1: Basic mail function
echo "<h3>Test 1: Basic PHP mail() function</h3>";

$to = "test@example.com";
$subject = "Test e-post fra Nasjonal 15m Jaktfeltcup";
$message = "
<html>
<head>
    <title>Test E-post</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class='header'>
        <h1>Test e-post fra Nasjonal 15m Jaktfeltcup</h1>
    </div>
    <div class='content'>
        <p>Dette er en test e-post for å sjekke at e-post funksjonaliteten fungerer.</p>
        <p>Sendt: " . date('Y-m-d H:i:s') . "</p>
        <p>Hvis du mottar denne e-posten, betyr det at e-post systemet fungerer!</p>
    </div>
</body>
</html>";

$headers = "From: " . $mail_config['from_address'] . "\r\n";
$headers .= "Reply-To: " . $mail_config['from_address'] . "\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

echo "<p><strong>To:</strong> $to</p>";
echo "<p><strong>From:</strong> " . $mail_config['from_address'] . "</p>";
echo "<p><strong>Subject:</strong> $subject</p>";

if (mail($to, $subject, $message, $headers)) {
    echo "<p style='color: green; font-weight: bold;'>✅ PHP mail() function works!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ PHP mail() function failed</p>";
    echo "<p>Last error: " . (error_get_last()['message'] ?? 'Unknown error') . "</p>";
}

// Test 2: Interactive test
echo "<h3>Test 2: Send til din e-post</h3>";
echo "<form method='post'>";
echo "<p><label>Din e-post adresse: <input type='email' name='user_email' placeholder='din@epost.no' required></label></p>";
echo "<p><button type='submit' name='send_test'>Send Test E-post</button></p>";
echo "</form>";

if (isset($_POST['send_test']) && !empty($_POST['user_email'])) {
    $user_email = $_POST['user_email'];
    
    $subject = "Test e-post fra Nasjonal 15m Jaktfeltcup";
    $message = "
    <html>
    <head>
        <title>Test E-post</title>
        <style>
            body { font-family: Arial, sans-serif; }
            .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background-color: #f9f9f9; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>Test e-post fra Nasjonal 15m Jaktfeltcup</h1>
        </div>
        <div class='content'>
            <p>Hei!</p>
            <p>Dette er en test e-post for å sjekke at e-post funksjonaliteten fungerer.</p>
            <p>Hvis du mottar denne e-posten, betyr det at e-post systemet fungerer!</p>
            <p>Sendt: " . date('Y-m-d H:i:s') . "</p>
        </div>
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

// Test 3: System check
echo "<h3>Test 3: System Check</h3>";

if (function_exists('mail')) {
    echo "<p style='color: green;'>✅ PHP mail() function is available</p>";
} else {
    echo "<p style='color: red;'>❌ PHP mail() function is NOT available</p>";
}

if (ini_get('sendmail_path')) {
    echo "<p style='color: green;'>✅ Sendmail path is configured: " . ini_get('sendmail_path') . "</p>";
} else {
    echo "<p style='color: orange;'>⚠️ Sendmail path not configured</p>";
}

// Test 4: Configuration
echo "<h3>Test 4: Konfigurasjon</h3>";
echo "<pre>";
print_r($mail_config);
echo "</pre>";

// Test 5: XAMPP Mail Setup
echo "<h3>Test 5: XAMPP Mail Setup</h3>";
echo "<p><strong>For at mail() skal fungere i XAMPP:</strong></p>";
echo "<ol>";
echo "<li>Åpne <code>php.ini</code> (vanligvis i <code>C:\\xampp\\php\\php.ini</code>)</li>";
echo "<li>Finn <code>[mail function]</code> seksjonen</li>";
echo "<li>Konfigurer:</li>";
echo "</ol>";
echo "<pre>";
echo "[mail function]\n";
echo "SMTP = " . $mail_config['host'] . "\n";
echo "smtp_port = " . $mail_config['port'] . "\n";
echo "sendmail_from = " . $mail_config['from_address'] . "\n";
echo "</pre>";
echo "<p><strong>Eller bruk en e-post service som MailHog for testing.</strong></p>";

echo "<h3>Instruksjoner:</h3>";
echo "<ol>";
echo "<li>Test med formen over for å sende til din e-post</li>";
echo "<li>Sjekk at e-posten kommer frem</li>";
echo "<li>Hvis det ikke fungerer, sjekk XAMPP mail-konfigurasjon</li>";
echo "<li>For produksjon, bruk din SMTP-server</li>";
echo "</ol>";
?>
