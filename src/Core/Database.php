<?php
/**
 * Database class for Jaktfeltcup
 */

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
