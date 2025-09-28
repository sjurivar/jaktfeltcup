<?php
/**
 * Save Inline Edit Handler
 * Handles AJAX requests for inline content editing
 */

session_start();

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../../src/Core/Database.php';
require_once __DIR__ . '/../../../src/Helpers/ContentHelper.php';

// Set JSON response header
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Ikke logget inn']);
    exit;
}

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);
$contentHelper = new \Jaktfeltcup\Helpers\ContentHelper($database);

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
    echo json_encode(['success' => false, 'message' => 'Kunne ikke hente brukerroller']);
    exit;
}

if (!in_array('contentmanager', $user_roles) && !in_array('admin', $user_roles)) {
    echo json_encode(['success' => false, 'message' => 'Du har ikke tilgang til innholdsredigering']);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page_key = $_POST['page_key'] ?? '';
    $section_key = $_POST['section_key'] ?? '';
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    
    if (empty($page_key) || empty($section_key)) {
        echo json_encode(['success' => false, 'message' => 'Manglende side- eller seksjonsnøkkel']);
        exit;
    }
    
    // Update content
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'Bruker-ID ikke funnet']);
        exit;
    }
    
    // Debug parameters
    error_log("save_inline_edit.php - Parameters: page_key=$page_key, section_key=$section_key, title=$title, content=$content, user_id=$user_id");
    
    $success = $contentHelper->updatePageContent(
        $page_key, 
        $section_key, 
        $title, 
        $content, 
        $user_id
    );
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Innhold oppdatert']);
    } else {
        // Get more detailed error information
        $error_details = "ContentHelper::updatePageContent failed for $page_key/$section_key";
        error_log("save_inline_edit.php - $error_details");
        echo json_encode(['success' => false, 'message' => 'Feil ved oppdatering av innhold: ' . $error_details]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Ugyldig forespørsel']);
}
?>
