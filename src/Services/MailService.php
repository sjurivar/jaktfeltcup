<?php

namespace Jaktfeltcup\Services;

use Jaktfeltcup\Core\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Mail Service
 * 
 * Handles sending emails using PHPMailer.
 */
class MailService
{
    private Config $config;
    private PHPMailer $mailer;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->initializeMailer();
    }

    /**
     * Initialize PHPMailer
     */
    private function initializeMailer(): void
    {
        $this->mailer = new PHPMailer(true);

        // Server settings
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->config->get('mail.host');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->config->get('mail.username');
        $this->mailer->Password = $this->config->get('mail.password');
        $this->mailer->SMTPSecure = $this->config->get('mail.encryption');
        $this->mailer->Port = $this->config->get('mail.port');

        // Recipients
        $this->mailer->setFrom(
            $this->config->get('mail.from_address'),
            $this->config->get('mail.from_name')
        );
    }

    /**
     * Send email
     */
    public function send(string $to, string $subject, string $body, bool $isHtml = false): bool
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);
            $this->mailer->isHTML($isHtml);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Mail sending failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send HTML email
     */
    public function sendHtml(string $to, string $subject, string $htmlBody): bool
    {
        return $this->send($to, $subject, $htmlBody, true);
    }

    /**
     * Send email with template
     */
    public function sendTemplate(string $to, string $subject, string $template, array $data = []): bool
    {
        $body = $this->renderTemplate($template, $data);
        return $this->sendHtml($to, $subject, $body);
    }

    /**
     * Render email template
     */
    private function renderTemplate(string $template, array $data = []): string
    {
        $templateFile = __DIR__ . "/../../templates/emails/{$template}.php";
        
        if (!file_exists($templateFile)) {
            throw new \Exception("Email template not found: {$template}");
        }

        extract($data);
        ob_start();
        include $templateFile;
        return ob_get_clean();
    }
}
