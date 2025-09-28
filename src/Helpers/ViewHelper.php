<?php
/**
 * View Helper functions for Jaktfeltcup
 */

// Load ImageHelper
require_once __DIR__ . '/ImageHelper.php';

// Helper function to get base URL
function base_url($path = '') {
    global $app_config;
    return $app_config['base_url'] . ($path ? '/' . ltrim($path, '/') : '');
}

// Helper function to include header
function include_header($header_file = 'header.php') {
    $header_path = __DIR__ . '/../../views/layouts/' . $header_file;
    
    if (file_exists($header_path)) {
        include $header_path;
    }
}

// Helper function to include footer
function include_footer($footer_file = 'footer.php') {
    $footer_path = __DIR__ . '/../../views/layouts/' . $footer_file;
    
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
        return new \Jaktfeltcup\Core\Database($GLOBALS['config']);
    }
}
