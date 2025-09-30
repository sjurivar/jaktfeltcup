<?php
/**
 * Email Test without Composer - Manual PHPMailer
 */

// Load configuration
require_once __DIR__ . '/config/config.php';

echo "<h2>E-post Test (Uten Composer)</h2>";

// Try to load PHPMailer manually
$phpmailer_loaded = false;
if (file_exists(__DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php')) {
    require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';
    require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';
    $phpmailer_loaded = true;
    echo "<p style='color: green;'>✅ PHPMailer loaded from vendor directory</p>";
} else {
    echo "<p style='color: orange;'>⚠️ PHPMailer not found - will use basic mail() function</p>";
}

// Test 1: Basic PHP mail function
echo "<h3>Test 1: Basic PHP mail() function</h3>";

$to = "test@example.com";
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

$headers = "From: " . $mail_config['from_address'] . "\r\n";
$headers .= "Reply-To: " . $mail_config['from_address'] . "\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

echo "<p><strong>To:</strong> $to</p>";
echo "<p><strong>From:</strong> " . $mail_config['from_address'] . "</p>";

if (mail($to, $subject, $message, $headers)) {
    echo "<p style='color: green; font-weight: bold;'>✅ Basic mail() function works!</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Basic mail() function failed</p>";
}

// Test 2: PHPMailer (if available)
if ($phpmailer_loaded) {
    echo "<h3>Test 2: PHPMailer SMTP</h3>";
    
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = $mail_config['host'];
        $mail->SMTPAuth = true;
        $mail->Username = $mail_config['username'];
        $mail->Password = $mail_config['password'];
        $mail->SMTPSecure = $mail_config['encryption'];
        $mail->Port = $mail_config['port'];
        
        // Recipients
        $mail->setFrom($mail_config['from_address'], $mail_config['from_name']);
        $mail->addAddress('test@example.com', 'Test User');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Test e-post fra Nasjonal 15m Jaktfeltcup (PHPMailer)';
        $mail->Body = '<h1>Test e-post</h1><p>Dette er en test e-post sendt via PHPMailer.</p>';
        
        $mail->send();
        echo "<p style='color: green; font-weight: bold;'>✅ PHPMailer SMTP mail sent successfully!</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red; font-weight: bold;'>❌ PHPMailer SMTP failed:</p>";
        echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    }
}

// Test 3: Interactive test
echo "<h3>Test 3: Send til din e-post</h3>";
echo "<form method='post'>";
echo "<p><label>Din e-post adresse: <input type='email' name='user_email' placeholder='din@epost.no' required></label></p>";
echo "<p><button type='submit' name='send_test'>Send Test E-post</button></p>";
echo "</form>";

if (isset($_POST['send_test']) && !empty($_POST['user_email'])) {
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
    
    $headers = "From: " . $mail_config['from_address'] . "\r\n";
    $headers .= "Reply-To: " . $mail_config['from_address'] . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    if (mail($user_email, $subject, $message, $headers)) {
        echo "<p style='color: green; font-weight: bold;'>✅ Test e-post sendt til $user_email!</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Kunne ikke sende e-post til $user_email</p>";
    }
}

// Test 4: Configuration check
echo "<h3>Test 4: Konfigurasjon</h3>";
echo "<pre>";
print_r($mail_config);
echo "</pre>";

// Test 5: System check
echo "<h3>Test 5: System Check</h3>";

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

echo "<h3>Instruksjoner:</h3>";
echo "<ol>";
echo "<li><strong>For basic e-post:</strong> Ingen ekstra installasjon nødvendig</li>";
echo "<li><strong>For SMTP e-post:</strong> Konfigurer SMTP-innstillinger i config.php</li>";
echo "<li><strong>For PHPMailer:</strong> Last ned manuelt eller bruk composer</li>";
echo "<li><strong>Test med formen:</strong> Send test e-post til din e-postadresse</li>";
echo "</ol>";

echo "<h3>Alternativer for PHPMailer:</h3>";
echo "<ol>";
echo "<li><strong>Composer:</strong> <code>composer install</code></li>";
echo "<li><strong>Manuell nedlasting:</strong> Last ned fra GitHub og legg i vendor/ mappe</li>";
echo "<li><strong>Bare mail():</strong> Bruk kun PHP mail() funksjonen</li>";
echo "</ol>";
?>
