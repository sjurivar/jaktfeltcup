<?php

namespace Jaktfeltcup\Services;

use Jaktfeltcup\Core\Config;
use Twilio\Rest\Client;

/**
 * SMS Service
 * 
 * Handles sending SMS messages using Twilio.
 */
class SmsService
{
    private Config $config;
    private ?Client $twilioClient;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->initializeTwilio();
    }

    /**
     * Initialize Twilio client
     */
    private function initializeTwilio(): void
    {
        $sid = $this->config->get('sms.twilio_sid');
        $token = $this->config->get('sms.twilio_token');

        if ($sid && $token) {
            $this->twilioClient = new Client($sid, $token);
        } else {
            $this->twilioClient = null;
        }
    }

    /**
     * Send SMS message
     */
    public function send(string $to, string $message): bool
    {
        if (!$this->twilioClient) {
            error_log("Twilio not configured");
            return false;
        }

        try {
            $from = $this->config->get('sms.twilio_phone');
            
            $this->twilioClient->messages->create(
                $to,
                [
                    'from' => $from,
                    'body' => $message
                ]
            );

            return true;
        } catch (\Exception $e) {
            error_log("SMS sending failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk SMS messages
     */
    public function sendBulk(array $recipients, string $message): array
    {
        $results = [];
        
        foreach ($recipients as $phone) {
            $results[$phone] = $this->send($phone, $message);
        }

        return $results;
    }

    /**
     * Send competition result SMS
     */
    public function sendResultSms(string $phone, array $competition, array $result): bool
    {
        $message = "Resultat fra {$competition['name']}: ";
        $message .= "Poeng: {$result['score']}, ";
        $message .= "Plassering: {$result['position']}, ";
        $message .= "Poeng tildelt: {$result['points_awarded']}";

        return $this->send($phone, $message);
    }

    /**
     * Send registration confirmation SMS
     */
    public function sendRegistrationSms(string $phone, array $competition): bool
    {
        $date = date('d.m.Y', strtotime($competition['competition_date']));
        $message = "Påmeldt til {$competition['name']} {$date} på {$competition['location']}. Lykke til!";

        return $this->send($phone, $message);
    }

    /**
     * Send competition reminder SMS
     */
    public function sendReminderSms(string $phone, array $competition): bool
    {
        $date = date('d.m.Y', strtotime($competition['competition_date']));
        $message = "Påminnelse: {$competition['name']} {$date} på {$competition['location']}. Husk å møte opp!";

        return $this->send($phone, $message);
    }
}
