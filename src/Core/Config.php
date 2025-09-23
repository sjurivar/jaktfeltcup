<?php

namespace Jaktfeltcup\Core;

/**
 * Configuration Management
 * 
 * Handles loading and accessing configuration values
 * from environment variables and config files.
 */
class Config
{
    private array $config = [];

    public function __construct()
    {
        $this->loadEnvironmentVariables();
    }

    /**
     * Load environment variables
     */
    private function loadEnvironmentVariables(): void
    {
        $this->config = [
            'app' => [
                'name' => $_ENV['APP_NAME'] ?? 'Jaktfeltcup',
                'env' => $_ENV['APP_ENV'] ?? 'production',
                'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
            ],
            'database' => [
                'host' => $_ENV['DB_HOST'] ?? 'localhost',
                'port' => $_ENV['DB_PORT'] ?? 3306,
                'name' => $_ENV['DB_NAME'] ?? 'jaktfeltcup',
                'user' => $_ENV['DB_USER'] ?? 'root',
                'password' => $_ENV['DB_PASSWORD'] ?? '',
            ],
            'security' => [
                'jwt_secret' => $_ENV['JWT_SECRET'] ?? '',
                'session_lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 7200),
            ],
            'mail' => [
                'host' => $_ENV['MAIL_HOST'] ?? '',
                'port' => (int)($_ENV['MAIL_PORT'] ?? 587),
                'username' => $_ENV['MAIL_USERNAME'] ?? '',
                'password' => $_ENV['MAIL_PASSWORD'] ?? '',
                'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
                'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? '',
                'from_name' => $_ENV['MAIL_FROM_NAME'] ?? '',
            ],
            'sms' => [
                'twilio_sid' => $_ENV['TWILIO_SID'] ?? '',
                'twilio_token' => $_ENV['TWILIO_TOKEN'] ?? '',
                'twilio_phone' => $_ENV['TWILIO_PHONE'] ?? '',
            ],
            'upload' => [
                'max_size' => (int)($_ENV['UPLOAD_MAX_SIZE'] ?? 10485760),
                'allowed_types' => explode(',', $_ENV['ALLOWED_FILE_TYPES'] ?? 'jpg,jpeg,png,pdf'),
            ],
            'offline' => [
                'enabled' => filter_var($_ENV['OFFLINE_MODE'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'sync_interval' => (int)($_ENV['OFFLINE_SYNC_INTERVAL'] ?? 300),
            ],
        ];
    }

    /**
     * Get configuration value
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    /**
     * Check if configuration key exists
     */
    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * Get all configuration
     */
    public function all(): array
    {
        return $this->config;
    }
}
