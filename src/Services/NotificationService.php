<?php

namespace Jaktfeltcup\Services;

use Jaktfeltcup\Core\Database;
use Jaktfeltcup\Services\MailService;
use Jaktfeltcup\Services\SmsService;

/**
 * Notification Service
 * 
 * Handles sending notifications via email and SMS.
 */
class NotificationService
{
    private Database $database;
    private MailService $mailService;
    private SmsService $smsService;

    public function __construct(Database $database, MailService $mailService, SmsService $smsService)
    {
        $this->database = $database;
        $this->mailService = $mailService;
        $this->smsService = $smsService;
    }

    /**
     * Send notification to user
     */
    public function sendNotification(int $userId, string $type, string $subject, string $message): bool
    {
        $user = $this->database->queryOne(
            "SELECT * FROM jaktfelt_users WHERE id = ? AND is_active = 1",
            [$userId]
        );

        if (!$user) {
            return false;
        }

        // Store notification in database
        $notificationId = $this->database->execute(
            "INSERT INTO notifications (user_id, type, subject, message, status) VALUES (?, ?, ?, ?, 'pending')",
            [$userId, $type, $subject, $message]
        );

        // Send notification based on type
        $success = false;
        switch ($type) {
            case 'email':
                $success = $this->mailService->send($user['email'], $subject, $message);
                break;
            case 'sms':
                if ($user['phone']) {
                    $success = $this->smsService->send($user['phone'], $message);
                }
                break;
        }

        // Update notification status
        $this->database->execute(
            "UPDATE notifications SET status = ?, sent_at = ? WHERE id = ?",
            [$success ? 'sent' : 'failed', $success ? date('Y-m-d H:i:s') : null, $notificationId]
        );

        return $success;
    }

    /**
     * Send bulk notifications
     */
    public function sendBulkNotifications(array $userIds, string $type, string $subject, string $message): array
    {
        $results = [];
        
        foreach ($userIds as $userId) {
            $results[$userId] = $this->sendNotification($userId, $type, $subject, $message);
        }

        return $results;
    }

    /**
     * Send competition result notification
     */
    public function sendResultNotification(int $userId, array $competition, array $result): bool
    {
        $subject = "Resultat fra {$competition['name']}";
        $message = "Hei!\n\nDitt resultat fra {$competition['name']}:\n";
        $message .= "Poeng: {$result['score']}\n";
        $message .= "Plassering: {$result['position']}\n";
        $message .= "Poeng tildelt: {$result['points_awarded']}\n\n";
        $message .= "Takk for deltakelse!";

        return $this->sendNotification($userId, 'email', $subject, $message);
    }

    /**
     * Send registration confirmation
     */
    public function sendRegistrationConfirmation(int $userId, array $competition): bool
    {
        $subject = "Påmeldingsbekreftelse - {$competition['name']}";
        $message = "Hei!\n\nDu er nå påmeldt til {$competition['name']}.\n";
        $message .= "Dato: " . date('d.m.Y', strtotime($competition['competition_date'])) . "\n";
        $message .= "Sted: {$competition['location']}\n\n";
        $message .= "Lykke til!";

        return $this->sendNotification($userId, 'email', $subject, $message);
    }

    /**
     * Send competition reminder
     */
    public function sendCompetitionReminder(int $userId, array $competition): bool
    {
        $subject = "Påminnelse - {$competition['name']}";
        $message = "Hei!\n\nDette er en påminnelse om at du er påmeldt til {$competition['name']}.\n";
        $message .= "Dato: " . date('d.m.Y', strtotime($competition['competition_date'])) . "\n";
        $message .= "Sted: {$competition['location']}\n\n";
        $message .= "Husk å møte opp!";

        return $this->sendNotification($userId, 'email', $subject, $message);
    }

    /**
     * Get notification history for user
     */
    public function getUserNotifications(int $userId, int $limit = 50): array
    {
        return $this->database->query(
            "SELECT * FROM notifications 
             WHERE user_id = ? 
             ORDER BY created_at DESC 
             LIMIT ?",
            [$userId, $limit]
        );
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId): bool
    {
        return $this->database->execute(
            "UPDATE notifications SET status = 'read' WHERE id = ?",
            [$notificationId]
        ) > 0;
    }
}
