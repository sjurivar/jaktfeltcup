<?php
/**
 * Update Text Content Handler
 * Handles text content updates
 */

session_start();

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../../src/Helpers/ContentHelper.php';
require_once __DIR__ . '/../../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('login'));
    exit;
}

// Check if user has content management access
$user_roles = [];
try {
    $user_roles = $database->queryAll(
        "SELECT r.role_name FROM jaktfelt_user_roles ur 
         JOIN jaktfelt_roles r ON ur.role_id = r.id 
         WHERE ur.user_id = ?", 
        [$_SESSION['user_id']]
    );
    $user_roles = array_column($user_roles, 'role_name');
} catch (Exception $e) {
    error_log("Could not fetch user roles: " . $e->getMessage());
}

if (!in_array('contentmanager', $user_roles) && !in_array('admin', $user_roles)) {
    $_SESSION['error'] = 'Du har ikke tilgang til innholdsredigering.';
    header('Location: ' . base_url('admin'));
    exit;
}

try {
    $page_key = $_POST['page_key'] ?? '';
    $content_data = $_POST['content'] ?? [];
    
    if (empty($page_key)) {
        throw new Exception('Side-nøkkel mangler.');
    }
    
    if (empty($content_data)) {
        throw new Exception('Ingen innhold å oppdatere.');
    }
    
    $updated_count = 0;
    
    foreach ($content_data as $section_key => $content) {
        $title = $content['title'] ?? '';
        $content_text = $content['content'] ?? '';
        
        if (!empty($title) || !empty($content_text)) {
            $success = update_page_content(
                $page_key, 
                $section_key, 
                $title, 
                $content_text, 
                $_SESSION['user_id']
            );
            
            if ($success) {
                $updated_count++;
            }
        }
    }
    
    if ($updated_count > 0) {
        $_SESSION['success'] = "Tekstinnhold oppdatert! ($updated_count seksjoner oppdatert)";
    } else {
        $_SESSION['error'] = 'Ingen endringer ble lagret.';
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    error_log("Update text content error: " . $e->getMessage());
}

// Redirect back to text management
header('Location: ' . base_url('admin/content/text?page=' . urlencode($page_key)));
exit;
