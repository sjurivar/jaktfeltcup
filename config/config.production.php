<?php
/**
 * Production Configuration
 * Copy this to config.php for production deployment
 */

// Database configuration
$db_config = [
    'host' => 'localhost',
    'port' => 3306,
    'name' => 'jaktfeltcup',
    'user' => 'jaktfeltcup',
    'password' => 'your_secure_password_here'
];

// Application configuration
$app_config = [
    'name' => 'Jaktfeltcup',
    'url' => 'https://hjellum.net/jaktfeltcup',
    'base_url' => '/jaktfeltcup', // Change to '' for root domain
    'debug' => false,
    'session_lifetime' => 7200,
    'data_source' => 'database' // Use database in production
];

// Email configuration
$mail_config = [
    'host' => 'smtp.yourdomain.com',
    'port' => 587,
    'username' => 'noreply@yourdomain.com',
    'password' => 'your_email_password',
    'encryption' => 'tls',
    'from_address' => 'noreply@yourdomain.com',
    'from_name' => 'Jaktfeltcup'
];

// Simple Database class
class Database {
    private $connection;
    private $config;
    
    public function __construct($config) {
        $this->config = $config;
        $this->connect();
    }
    
    private function connect() {
        $dsn = "mysql:host={$this->config['host']};port={$this->config['port']};dbname={$this->config['name']};charset=utf8mb4";
        
        try {
            $this->connection = new PDO($dsn, $this->config['user'], $this->config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function queryOne($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    public function execute($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}

// Initialize configuration
$config = $db_config;

// Helper function to get base URL
function base_url($path = '') {
    global $app_config;
    return $app_config['base_url'] . ($path ? '/' . ltrim($path, '/') : '');
}
