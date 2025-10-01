<?php
/**
 * Example configuration file for Jaktfeltcup
 * Copy this file to config.php and customize for your environment
 */

// Database configuration
$db_config = [
    'host' => 'localhost',
    'port' => 3306,
    'name' => 'jaktfeltcup',
    'user' => 'root',
    'password' => 'your_password_here'
];

// Database configuration
$db_config = [
    'host' => 'mysql.csj1wp95j.service.one',
    'port' => 3306,
    'name' => 'csj1wp95j_db1415790',
    'user' => 'csj1wp95j_db1415790',
    'password' => 'iDU!KUkDaZAS4Aa'
];

// Application configuration
$app_config = [
    'name' => 'Jaktfeltcup',
    'url' => 'http://localhost/jaktfeltcup',
    'base_url' => '/jaktfeltcup', // Change to '' for production
    'debug' => true,
    'session_lifetime' => 7200,
    'data_source' => 'database' // 'database' or 'json'
];

// Email configuration (optional)
$mail_config = [
    'host' => 'your_smtp_host',
    'port' => 587,
    'username' => 'your_username',
    'password' => 'your_password',
    'encryption' => 'tls',
    'from_address' => 'noreply@yourdomain.com',
    'from_name' => 'Jaktfeltcup'
];

// Mailjet configuration
$mailjet_config = [
    'api_key' => 'YOUR_MAILJET_API_KEY',        // Get from Mailjet dashboard
    'secret_key' => 'YOUR_MAILJET_SECRET_KEY',  // Get from Mailjet dashboard
    'from_email' => 'noreply@yourdomain.com',
    'from_name' => 'Jaktfeltcup'
];

// Initialize configuration
$config = $db_config;