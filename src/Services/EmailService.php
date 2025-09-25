<?php

class EmailService {
    private $config;
    private $db;
    
    public function __construct($config, $db) {
        $this->config = $config;
        $this->db = $db;
    }
    
    /**
     * Send email verification code
     */
    public function sendVerificationCode($userId, $email, $firstName) {
        // Generate verification code
        $verificationCode = $this->generateVerificationCode();
        
        // Store verification code in database
        $this->storeVerificationCode($userId, $email, $verificationCode);
        
        // Send email
        $subject = "Bekreft din e-postadresse - Jaktfeltcup";
        $message = $this->getVerificationEmailTemplate($firstName, $verificationCode);
        
        return $this->sendEmail($email, $subject, $message);
    }
    
    /**
     * Generate a secure verification code
     */
    private function generateVerificationCode() {
        return strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    }
    
    /**
     * Store verification code in database
     */
    private function storeVerificationCode($userId, $email, $code) {
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $sql = "INSERT INTO jaktfelt_email_verifications (user_id, email, verification_code, expires_at) 
                VALUES (?, ?, ?, ?)";
        
        $this->db->execute($sql, [$userId, $email, $code, $expiresAt]);
    }
    
    /**
     * Verify email code
     */
    public function verifyEmailCode($code) {
        $sql = "SELECT * FROM jaktfelt_email_verifications 
                WHERE verification_code = ? 
                AND is_used = FALSE 
                AND expires_at > NOW()";
        
        $verification = $this->db->queryOne($sql, [$code]);
        
        if (!$verification) {
            return false;
        }
        
        // Mark code as used
        $this->markCodeAsUsed($verification['id']);
        
        // Update user email_verified status
        $this->updateUserEmailVerified($verification['user_id']);
        
        return $verification;
    }
    
    /**
     * Mark verification code as used
     */
    private function markCodeAsUsed($verificationId) {
        $sql = "UPDATE jaktfelt_email_verifications 
                SET is_used = TRUE, used_at = NOW() 
                WHERE id = ?";
        
        $this->db->execute($sql, [$verificationId]);
    }
    
    /**
     * Update user email verified status
     */
    private function updateUserEmailVerified($userId) {
        $sql = "UPDATE jaktfelt_users 
                SET email_verified = TRUE 
                WHERE id = ?";
        
        $this->db->execute($sql, [$userId]);
    }
    
    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail($userId, $email, $firstName, $resetToken) {
        try {
            $resetUrl = $this->config['url'] . '/reset-password?token=' . $resetToken;
            
            $subject = 'Tilbakestill passord - Jaktfeltcup';
            $message = $this->getPasswordResetEmailTemplate($firstName, $resetUrl);
            
            // Log the email attempt
            error_log("📧 Attempting to send password reset email to: $email");
            error_log("📧 Reset URL: $resetUrl");
            
            // Try to send actual email
            $sent = $this->sendEmail($email, $subject, $message);
            
            if ($sent) {
                error_log("✅ Password reset email sent successfully to: $email");
            } else {
                error_log("❌ Failed to send password reset email to: $email");
            }
            
            return $sent;
            
        } catch (Exception $e) {
            error_log("Failed to send password reset email: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get verification email template
     */
    private function getVerificationEmailTemplate($firstName, $code) {
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
                    <h1>Velkommen til Jaktfeltcup!</h1>
                </div>
                <div class='content'>
                    <p>Hei $firstName,</p>
                    <p>Takk for at du registrerte deg for Jaktfeltcup. For å aktivere kontoen din, må du bekrefte din e-postadresse.</p>
                    
                    <p>Din bekreftelseskode er:</p>
                    <div class='code'>$code</div>
                    
                    <p>Du kan også klikke på denne lenken for å bekrefte:</p>
                    <p><a href='$baseUrl/verify-email?code=$code' style='background-color: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Bekreft e-postadresse</a></p>
                    
                    <p><strong>Viktig:</strong> Denne koden utløper om 24 timer.</p>
                    
                    <p>Hvis du ikke registrerte deg for Jaktfeltcup, kan du trygt ignorere denne e-posten.</p>
                </div>
                <div class='footer'>
                    <p>Jaktfeltcup - Administrasjon av skytekonkurranser</p>
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
            <title>Tilbakestill passord - Jaktfeltcup</title>
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
                    
                    <p>Du har bedt om å tilbakestille passordet ditt for Jaktfeltcup.</p>
                    
                    <p>Klikk på knappen under for å tilbakestille passordet ditt:</p>
                    
                    <a href='$resetUrl' class='button'>Tilbakestill passord</a>
                    
                    <p>Eller kopier og lim inn denne lenken i nettleseren din:</p>
                    <p style='word-break: break-all; background-color: #e9ecef; padding: 10px; border-radius: 3px;'>$resetUrl</p>
                    
                    <p><strong>Viktig:</strong> Denne lenken utløper om 1 time.</p>
                    
                    <p>Hvis du ikke ba om å tilbakestille passordet ditt, kan du trygt ignorere denne e-posten.</p>
                </div>
                <div class='footer'>
                    <p>Jaktfeltcup - Administrasjon av skytekonkurranser</p>
                    <p>Denne e-posten ble sendt automatisk, vennligst ikke svar på den.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Send email using PHP mail function (public method for testing)
     */
    public function sendEmail($to, $subject, $message) {
        // Log email attempt
        error_log("📧 Attempting to send email to: $to");
        error_log("📧 Subject: $subject");
        
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: Jaktfeltcup <noreply@jaktfeltcup.no>',
            'Reply-To: noreply@jaktfeltcup.no',
            'X-Mailer: PHP/' . phpversion()
        ];
        
        $headersString = implode("\r\n", $headers);
        
        // Check if mail function is available
        if (!function_exists('mail')) {
            error_log("❌ PHP mail() function is not available");
            return false;
        }
        
        // Try to send email
        $result = mail($to, $subject, $message, $headersString);
        
        if ($result) {
            error_log("✅ Email sent successfully to: $to");
        } else {
            error_log("❌ Failed to send email to: $to");
            error_log("❌ Last error: " . error_get_last()['message'] ?? 'Unknown error');
        }
        
        return $result;
    }
    
    /**
     * Send password reset email
     */
    public function sendPasswordReset($email, $resetCode) {
        $subject = "Tilbakestill passord - Jaktfeltcup";
        $message = $this->getPasswordResetTemplate($resetCode);
        
        return $this->sendEmail($email, $subject, $message);
    }
    
    /**
     * Get password reset email template
     */
    private function getPasswordResetTemplate($resetCode) {
        $baseUrl = $this->config['url'];
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Tilbakestill passord</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #e74c3c; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .code { background-color: #2c3e50; color: white; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; }
                .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Tilbakestill passord</h1>
                </div>
                <div class='content'>
                    <p>Du har bedt om å tilbakestille passordet ditt for Jaktfeltcup.</p>
                    
                    <p>Din tilbakestillingskode er:</p>
                    <div class='code'>$resetCode</div>
                    
                    <p>Du kan også klikke på denne lenken:</p>
                    <p><a href='$baseUrl/reset-password?code=$resetCode' style='background-color: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Tilbakestill passord</a></p>
                    
                    <p><strong>Viktig:</strong> Denne koden utløper om 1 time.</p>
                    
                    <p>Hvis du ikke ba om å tilbakestille passordet, kan du trygt ignorere denne e-posten.</p>
                </div>
                <div class='footer'>
                    <p>Jaktfeltcup - Administrasjon av skytekonkurranser</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
