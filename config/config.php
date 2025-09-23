<?php
/**
 * Configuration file for Jaktfeltcup
 * Simple configuration without external dependencies
 */

// Database configuration
$db_config = [
    'host' => 'localhost',
    'port' => 3306,
    'name' => 'jaktfeltcup',
    'user' => 'root',
    'password' => ''
];

// Application configuration
$app_config = [
    'name' => 'Jaktfeltcup',
    'url' => 'http://localhost/jaktfeltcup',
    'base_url' => '/jaktfeltcup', // Change to '' for production
    'debug' => true,
    'session_lifetime' => 7200,
    'data_source' => 'json' // 'database' or 'json'
];

// Email configuration (optional)
$mail_config = [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => '',
    'password' => '',
    'encryption' => 'tls',
    'from_address' => 'noreply@jaktfeltcup.no',
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

// Helper function to include header
function include_header($header_file = 'header.php') {
    $header_path = __DIR__ . '/../views/layouts/' . $header_file;
    
    if (file_exists($header_path)) {
        include $header_path;
    }
}

// Helper function to include footer
function include_footer($footer_file = 'footer.php') {
    $footer_path = __DIR__ . '/../views/layouts/' . $footer_file;
    
    if (file_exists($footer_path)) {
        include $footer_path;
    }
}

// Data service factory
function getDataService() {
    global $app_config;
    
    if ($app_config['data_source'] === 'json') {
        return new \Jaktfeltcup\Data\JsonDataService();
    } else {
        return new Database($GLOBALS['config']);
    }
}
