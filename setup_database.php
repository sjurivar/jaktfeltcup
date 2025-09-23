<?php
/**
 * Database Setup Script
 * Run this once to set up the database
 */

echo "<h2>Jaktfeltcup Database Setup</h2>";

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'jaktfeltcup';

try {
    // Connect to MySQL without database
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to MySQL</p>";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p>✅ Database '$dbname' created/verified</p>";
    
    // Select the database
    $pdo->exec("USE `$dbname`");
    
    // Read and execute schema
    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    if ($schema) {
        $pdo->exec($schema);
        echo "<p>✅ Database schema imported successfully</p>";
    } else {
        echo "<p>❌ Could not read schema file</p>";
    }
    
    echo "<p><strong>Database setup complete!</strong></p>";
    echo "<p><a href='/jaktfeltcup/'>Go to Jaktfeltcup</a></p>";
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure XAMPP MySQL is running and credentials are correct.</p>";
}
?>
