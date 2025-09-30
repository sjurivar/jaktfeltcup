<?php
/**
 * EmailService with PHPMailer support
 */

// Include PHPMailer (if available)
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
}

class EmailServicePHPMailer {
    private $config;
    private $db;
    private $mail_config;
    
    public function __construct($config, $db = null, $mail_config) {
        $this->config = $config;
        $this->db = $db;
        $this->mail_config = $mail_config;
    }
    
    /**
     * Send email using PHPMailer (if available) or fallback to mail()
     */
    public function sendEmail($to, $subject, $message, $isHTML = true) {
        // Log email attempt
        error_log("📧 Attempting to send email to: $to");
        error_log("📧 Subject: $subject");
        
        // Try PHPMailer first if available
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            return $this->sendWithPHPMailer($to, $subject, $message, $isHTML);
        } else {
            // Fallback to basic mail() function
            return $this->sendWithMailFunction($to, $subject, $message, $isHTML);
        }
    }
    
    /**
     * Send email using PHPMailer
     */
    private function sendWithPHPMailer($to, $subject, $message, $isHTML = true) {
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host = $this->mail_config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->mail_config['username'];
            $mail->Password = $this->mail_config['password'];
            $mail->SMTPSecure = $this->mail_config['encryption'];
            $mail->Port = $this->mail_config['port'];
            
            // Enable verbose debug output (for testing)
            // $mail->SMTPDebug = 2;
            
            // Recipients
            $mail->setFrom($this->mail_config['from_address'], $this->mail_config['from_name']);
            $mail->addAddress($to);
            
            // Content
            $mail->isHTML($isHTML);
            $mail->Subject = $subject;
            $mail->Body = $message;
            
            $mail->send();
            error_log("✅ Email sent successfully via PHPMailer to: $to");
            return true;
            
        } catch (Exception $e) {
            error_log("❌ PHPMailer failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send email using basic mail() function
     */
    private function sendWithMailFunction($to, $subject, $message, $isHTML = true) {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: ' . ($isHTML ? 'text/html' : 'text/plain') . '; charset=UTF-8',
            'From: ' . $this->mail_config['from_name'] . ' <' . $this->mail_config['from_address'] . '>',
            'Reply-To: ' . $this->mail_config['from_address'],
            'X-Mailer: PHP/' . phpversion()
        ];
        
        $headersString = implode("\r\n", $headers);
        
        // Check if mail function is available
        if (!function_exists('mail')) {
            error_log("❌ PHP mail() function is not available");
            return false;
        }
        
        $result = mail($to, $subject, $message, $headersString);
        
        if ($result) {
            error_log("✅ Email sent successfully via mail() to: $to");
        } else {
            error_log("❌ Failed to send email via mail() to: $to");
        }
        
        return $result;
    }
    
    /**
     * Send verification email
     */
    public function sendVerificationEmail($to, $firstName, $verificationCode) {
        $subject = "Bekreft din e-postadresse - Nasjonal 15m Jaktfeltcup";
        $message = $this->getVerificationEmailTemplate($firstName, $verificationCode);
        
        return $this->sendEmail($to, $subject, $message);
    }
    
    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail($to, $firstName, $resetUrl) {
        $subject = "Tilbakestill passord - Nasjonal 15m Jaktfeltcup";
        $message = $this->getPasswordResetEmailTemplate($firstName, $resetUrl);
        
        return $this->sendEmail($to, $subject, $message);
    }
    
    /**
     * Get verification email template
     */
    private function getVerificationEmailTemplate($firstName, $verificationCode) {
        $baseUrl = $this->config['url'];
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Bekreft din e-postadresse</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .code { background-color: #e74c3c; color: white; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; }
                .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Velkommen til Nasjonal 15m Jaktfeltcup!</h1>
                </div>
                <div class='content'>
                    <p>Hei $firstName,</p>
                    <p>Takk for at du registrerte deg for Nasjonal 15m Jaktfeltcup. For å aktivere kontoen din, må du bekrefte din e-postadresse.</p>
                    
                    <p>Din bekreftelseskode er:</p>
                    <div class='code'>$verificationCode</div>
                    
                    <p>Du kan også klikke på denne lenken for å bekrefte:</p>
                    <p><a href='$baseUrl/verify-email?code=$verificationCode' style='background-color: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Bekreft e-postadresse</a></p>
                    
                    <p><strong>Viktig:</strong> Denne koden utløper om 24 timer.</p>
                    
                    <p>Hvis du ikke registrerte deg for Nasjonal 15m Jaktfeltcup, kan du trygt ignorere denne e-posten.</p>
                </div>
                <div class='footer'>
                    <p>Nasjonal 15m Jaktfeltcup - Innendørs jaktfelt-konkurranse</p>
                    <p>Denne e-posten ble sendt automatisk, vennligst ikke svar på den.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Get password reset email template
     */
    private function getPasswordResetEmailTemplate($firstName, $resetUrl) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Tilbakestill passord - Nasjonal 15m Jaktfeltcup</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f8f9fa; padding: 30px; }
                .button { display: inline-block; background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { background-color: #e9ecef; padding: 20px; text-align: center; font-size: 12px; color: #6c757d; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🔑 Tilbakestill passord</h1>
                </div>
                <div class='content'>
                    <h2>Hei $firstName!</h2>
                    
                    <p>Du har bedt om å tilbakestille passordet ditt for Nasjonal 15m Jaktfeltcup.</p>
                    
                    <p>Klikk på knappen under for å tilbakestille passordet ditt:</p>
                    
                    <a href='$resetUrl' class='button'>Tilbakestill passord</a>
                    
                    <p>Eller kopier og lim inn denne lenken i nettleseren din:</p>
                    <p style='word-break: break-all; background-color: #e9ecef; padding: 10px; border-radius: 3px;'>$resetUrl</p>
                    
                    <p><strong>Viktig:</strong> Denne lenken utløper om 1 time.</p>
                    
                    <p>Hvis du ikke ba om å tilbakestille passordet ditt, kan du trygt ignorere denne e-posten.</p>
                </div>
                <div class='footer'>
                    <p>Nasjonal 15m Jaktfeltcup - Innendørs jaktfelt-konkurranse</p>
                    <p>Denne e-posten ble sendt automatisk, vennligst ikke svar på den.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
?>
