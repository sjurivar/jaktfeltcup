<?php
/**
 * Test Email Functionality
 */

// Load configuration
require_once __DIR__ . '/config/config.php';

echo "<h2>E-post Test</h2>";

// Check if mail configuration is set
echo "<h3>Mail Configuration:</h3>";
echo "<pre>";
print_r($mail_config);
echo "</pre>";

// Test 1: Basic PHP mail function
echo "<h3>Test 1: Basic PHP mail() function</h3>";
$to = "test@example.com";
$subject = "Test e-post fra Jaktfeltcup";
$message = "Dette er en test e-post for å sjekke om e-post fungerer.";
$headers = "From: " . $mail_config['from_address'] . "\r\n";
$headers .= "Reply-To: " . $mail_config['from_address'] . "\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

echo "<p><strong>To:</strong> $to</p>";
echo "<p><strong>Subject:</strong> $subject</p>";
echo "<p><strong>From:</strong> " . $mail_config['from_address'] . "</p>";

if (mail($to, $subject, $message, $headers)) {
    echo "<p style='color: green;'><strong>✅ Basic mail() function works!</strong></p>";
} else {
    echo "<p style='color: red;'><strong>❌ Basic mail() function failed</strong></p>";
}

// Test 2: SMTP with PHPMailer (if available)
echo "<h3>Test 2: SMTP with PHPMailer</h3>";

// Check if PHPMailer is available
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "<p>PHPMailer is available</p>";
    
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
        $mail->Subject = 'Test e-post fra Jaktfeltcup (SMTP)';
        $mail->Body = '<h1>Test e-post</h1><p>Dette er en test e-post sendt via SMTP.</p>';
        
        $mail->send();
        echo "<p style='color: green;'><strong>✅ SMTP mail sent successfully!</strong></p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'><strong>❌ SMTP mail failed:</strong> " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: orange;'><strong>⚠️ PHPMailer not available</strong></p>";
    echo "<p>To install PHPMailer, run: <code>composer require phpmailer/phpmailer</code></p>";
}

// Test 3: Check mail configuration
echo "<h3>Test 3: Configuration Check</h3>";

$issues = [];
if (empty($mail_config['username'])) {
    $issues[] = "SMTP username is empty";
}
if (empty($mail_config['password'])) {
    $issues[] = "SMTP password is empty";
}
if (empty($mail_config['from_address'])) {
    $issues[] = "From address is empty";
}

if (empty($issues)) {
    echo "<p style='color: green;'><strong>✅ Mail configuration looks good</strong></p>";
} else {
    echo "<p style='color: red;'><strong>❌ Configuration issues:</strong></p>";
    echo "<ul>";
    foreach ($issues as $issue) {
        echo "<li>$issue</li>";
    }
    echo "</ul>";
}

// Test 4: Simple email test with actual sending
echo "<h3>Test 4: Send Test Email</h3>";
echo "<form method='post'>";
echo "<p><label>Test e-post adresse: <input type='email' name='test_email' placeholder='din@epost.no' required></label></p>";
echo "<p><button type='submit' name='send_test'>Send Test E-post</button></p>";
echo "</form>";

if (isset($_POST['send_test']) && !empty($_POST['test_email'])) {
    $test_email = $_POST['test_email'];
    
    $subject = "Test e-post fra Nasjonal 15m Jaktfeltcup";
    $message = "
    <html>
    <head>
        <title>Test E-post</title>
    </head>
    <body>
        <h2>Test e-post fra Nasjonal 15m Jaktfeltcup</h2>
        <p>Dette er en test e-post for å sjekke at e-post funksjonaliteten fungerer.</p>
        <p>Hvis du mottar denne e-posten, betyr det at e-post systemet fungerer!</p>
        <p>Sendt: " . date('Y-m-d H:i:s') . "</p>
    </body>
    </html>";
    
    $headers = "From: " . $mail_config['from_address'] . "\r\n";
    $headers .= "Reply-To: " . $mail_config['from_address'] . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    if (mail($test_email, $subject, $message, $headers)) {
        echo "<p style='color: green;'><strong>✅ Test e-post sent to $test_email!</strong></p>";
    } else {
        echo "<p style='color: red;'><strong>❌ Failed to send test e-post</strong></p>";
    }
}

echo "<h3>Instructions:</h3>";
echo "<ol>";
echo "<li>Fill in your SMTP credentials in config.php</li>";
echo "<li>Make sure your e-mail provider allows SMTP access</li>";
echo "<li>For Gmail, you may need to use an 'App Password' instead of your regular password</li>";
echo "<li>Test with the form above</li>";
echo "</ol>";
?>
