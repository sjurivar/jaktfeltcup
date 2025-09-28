<?php
/**
 * Application initialization
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';

// Load core classes
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../Helpers/ViewHelper.php';

// Initialize database connection
$database = new \Jaktfeltcup\Core\Database($db_config);
