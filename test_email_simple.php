<?php
/**
 * Simple Email Test (No Database Required)
 */

// Load configuration
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Services/EmailServicePHPMailer.php';

echo "<h2>Enkel E-post Test (Uten Database)</h2>";

try {
    // Initialize email service without database
    $emailService = new EmailServicePHPMailer($app_config, null, $mail_config);
    
    echo "<p style='color: green;'><strong>✅ EmailServicePHPMailer initialized successfully</strong></p>";
    
    // Check PHPMailer availability
    echo "<h3>PHPMailer Status:</h3>";
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        echo "<p style='color: green;'>✅ PHPMailer is available</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ PHPMailer not available - will use fallback mail() function</p>";
        echo "<p>To install PHPMailer, run: <code>composer install</code></p>";
    }
    
    // Test 1: Basic email sending
    echo "<h3>Test 1: Basic Email Sending</h3>";
    
    $test_email = "test@example.com";
    $subject = "Test e-post fra Nasjonal 15m Jaktfeltcup";
    $message = "
    <html>
    <head><title>Test E-post</title></head>
    <body>
        <h2>Test e-post fra Nasjonal 15m Jaktfeltcup</h2>
        <p>Dette er en test e-post for å sjekke at e-post funksjonaliteten fungerer.</p>
        <p>Sendt: " . date('Y-m-d H:i:s') . "</p>
    </body>
    </html>";
    
    echo "<p><strong>Sender test e-post til:</strong> $test_email</p>";
    
    $result = $emailService->sendEmail($test_email, $subject, $message);
    
    if ($result) {
        echo "<p style='color: green; font-weight: bold;'>✅ E-post sendt suksessfullt!</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Kunne ikke sende e-post</p>";
    }
    
    // Test 2: Interactive test
    echo "<h3>Test 2: Send til din e-post</h3>";
    echo "<form method='post'>";
    echo "<p><label>Din e-post adresse: <input type='email' name='user_email' placeholder='din@epost.no' required></label></p>";
    echo "<p><button type='submit' name='send_user_test'>Send Test E-post</button></p>";
    echo "</form>";
    
    if (isset($_POST['send_user_test']) && !empty($_POST['user_email'])) {
        $user_email = $_POST['user_email'];
        
        $subject = "Test e-post fra Nasjonal 15m Jaktfeltcup";
        $message = "
        <html>
        <head><title>Test E-post</title></head>
        <body>
            <h2>Test e-post fra Nasjonal 15m Jaktfeltcup</h2>
            <p>Hei!</p>
            <p>Dette er en test e-post for å sjekke at e-post funksjonaliteten fungerer.</p>
            <p>Hvis du mottar denne e-posten, betyr det at e-post systemet fungerer!</p>
            <p>Sendt: " . date('Y-m-d H:i:s') . "</p>
        </body>
        </html>";
        
        $result = $emailService->sendEmail($user_email, $subject, $message);
        
        if ($result) {
            echo "<p style='color: green; font-weight: bold;'>✅ Test e-post sendt til $user_email!</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>❌ Kunne ikke sende e-post til $user_email</p>";
        }
    }
    
    // Test 3: Check configuration
    echo "<h3>Test 3: Konfigurasjon</h3>";
    echo "<pre>";
    print_r($mail_config);
    echo "</pre>";
    
    // Test 4: System check
    echo "<h3>Test 4: System Check</h3>";
    
    if (function_exists('mail')) {
        echo "<p style='color: green;'>✅ PHP mail() function is available</p>";
    } else {
        echo "<p style='color: red;'>❌ PHP mail() function is NOT available</p>";
    }
    
    // Check SMTP settings
    if (!empty($mail_config['username']) && !empty($mail_config['password'])) {
        echo "<p style='color: green;'>✅ SMTP credentials are configured</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ SMTP credentials are not configured</p>";
        echo "<p>For å bruke SMTP, fyll inn username og password i config.php</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
}

echo "<h3>Instruksjoner:</h3>";
echo "<ol>";
echo "<li><strong>Installer PHPMailer:</strong> <code>composer install</code></li>";
echo "<li><strong>Konfigurer SMTP:</strong> Fyll inn username og password i config.php</li>";
echo "<li><strong>Test med formen:</strong> Send test e-post til din e-postadresse</li>";
echo "<li><strong>Sjekk e-post:</strong> Se om e-postene kommer frem</li>";
echo "</ol>";
?>
