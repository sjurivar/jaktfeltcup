<?php
/**
 * Example Configuration file for Jaktfeltcup
 * Copy this file to config.php and update with your settings
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
