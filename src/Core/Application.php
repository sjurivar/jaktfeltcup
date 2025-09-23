<?php

namespace Jaktfeltcup\Core;

/**
 * Main Application Class
 * 
 * Handles application initialization, dependency injection,
 * and core application functionality.
 */
class Application
{
    private Config $config;
    private Database $database;
    private array $services = [];

    public function __construct(Config $config, Database $database)
    {
        $this->config = $config;
        $this->database = $database;
        $this->initializeServices();
    }

    /**
     * Initialize core services
     */
    private function initializeServices(): void
    {
        $this->services['auth'] = new Auth\AuthService($this->database);
        $this->services['mail'] = new Services\MailService($this->config);
        $this->services['sms'] = new Services\SmsService($this->config);
        $this->services['notification'] = new Services\NotificationService(
            $this->database,
            $this->services['mail'],
            $this->services['sms']
        );
    }

    /**
     * Get a service instance
     */
    public function getService(string $name): mixed
    {
        return $this->services[$name] ?? null;
    }

    /**
     * Get configuration
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Get database connection
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }
}
