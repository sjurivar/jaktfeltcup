### Services

Service classes encapsulate integrations and cross-cutting concerns like email, SMS, and notifications.

---

### AuthService (`Jaktfeltcup\Auth\AuthService`)

Although placed under `src/Auth/`, it is a core service used by controllers and middleware.

Public API:
- `__construct(Database $database)`
- `register(array $userData): array` — Create a new user; validates input; returns created user.
- `login(string $email, string $password): array` — Verify credentials; starts session; returns user.
- `logout(): void` — Destroy session.
- `isAuthenticated(): bool` — True if a session user is present.
- `getCurrentUser(): ?array` — Returns current user or null.
- `hasRole(string $role): bool` — Check session user role.
- `isAdmin(): bool`, `isOrganizer(): bool`, `isParticipant(): bool`
- `getUserById(int $userId): ?array`
- `updateProfile(int $userId, array $data): bool` — Update mutable profile fields; optional password.

Example:
```php
$auth = new Jaktfeltcup\Auth\AuthService($db);
try {
  $user = $auth->login('user@example.com', 'secret');
} catch (Exception $e) {
  // handle invalid credentials
}
```

---

### MailService (`Jaktfeltcup\Services\MailService`)

PHPMailer-based SMTP email sender.

Public API:
- `__construct(Config $config)`
- `send(string $to, string $subject, string $body, bool $isHtml = false): bool`
- `sendHtml(string $to, string $subject, string $htmlBody): bool`
- `sendTemplate(string $to, string $subject, string $template, array $data = []): bool` — Renders `templates/emails/{template}.php` with `$data`.

Example:
```php
$mail = new Jaktfeltcup\Services\MailService($config);
$mail->sendHtml('user@example.com', 'Welcome', '<h1>Hello</h1>');
```

---

### SmsService (`Jaktfeltcup\Services\SmsService`)

Twilio-based SMS sender.

Public API:
- `__construct(Config $config)`
- `send(string $to, string $message): bool`
- `sendBulk(array $recipients, string $message): array` — Map of phone → success bool.
- `sendResultSms(string $phone, array $competition, array $result): bool`
- `sendRegistrationSms(string $phone, array $competition): bool`
- `sendReminderSms(string $phone, array $competition): bool`

Example:
```php
$sms = new Jaktfeltcup\Services\SmsService($config);
$sms->send('+4712345678', 'Hei!');
```

---

### NotificationService (`Jaktfeltcup\Services\NotificationService`)

Coordinates email and SMS notifications and persists status to DB.

Public API:
- `__construct(Database $database, MailService $mailService, SmsService $smsService)`
- `sendNotification(int $userId, string $type, string $subject, string $message): bool` — type ∈ {`email`, `sms`}.
- `sendBulkNotifications(array $userIds, string $type, string $subject, string $message): array`
- `sendResultNotification(int $userId, array $competition, array $result): bool`
- `sendRegistrationConfirmation(int $userId, array $competition): bool`
- `sendCompetitionReminder(int $userId, array $competition): bool`
- `getUserNotifications(int $userId, int $limit = 50): array`
- `markAsRead(int $notificationId): bool`

Example:
```php
$notify = new Jaktfeltcup\Services\NotificationService($db, $mail, $sms);
$notify->sendNotification(123, 'email', 'Subject', 'Message body');
```

---

### EmailService (`src/Services/EmailService.php`)

Standalone utility for verification/password-reset emails using native `mail()` and DB records.

Public API:
- `__construct(array $config, Database $db)`
- `sendVerificationCode($userId, $email, $firstName)`
- `verifyEmailCode($code)`
- `sendPasswordResetEmail($userId, $email, $firstName, $resetToken)`
- `sendEmail($to, $subject, $message)`
- `sendPasswordReset($email, $resetCode)`

Example:
```php
$emailSvc = new EmailService(['url' => 'http://localhost:8000'], $db);
$emailSvc->sendPasswordReset('user@example.com', 'ABC12345');
```

