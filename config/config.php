<?php
/**
 * Configuration file for Jaktfeltcup
 * This file should be customized for your environment
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
    'data_source' => 'database' // 'database' or 'json'
];

// Email configuration (optional)
$mail_config = [
    'host' => 'mailout.proisp.no',
    'port' => 587,
    'username' => 'noreply',
    'password' => 'j$!x)9~dB2p=',
    'encryption' => 'tls',
    'from_address' => 'noreply@jaktfeltcup.no',
    'from_name' => 'Jaktfeltcup'
];


// Initialize configuration
$config = $db_config;
