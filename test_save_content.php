<?php
/**
 * Test Save Content
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Helpers/ViewHelper.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Helpers/ContentHelper.php';

echo "<h1>🧪 Test Save Content</h1>";

// Test data
$page_key = 'arrangor';
$section_key = 'hero_title';
$title = 'Test Title';
$content = 'Test Content';

echo "<h2>Test Data:</h2>";
echo "<p>page_key: $page_key</p>";
echo "<p>section_key: $section_key</p>";
echo "<p>title: $title</p>";
echo "<p>content: $content</p>";

// Initialize database
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);
$contentHelper = new \Jaktfeltcup\Helpers\ContentHelper($database);

echo "<h2>Database Test:</h2>";
try {
    $test_query = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_page_content");
    echo "<p style='color: green;'>✅ Database connection OK. Content records: " . $test_query['count'] . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<h2>ContentHelper Test:</h2>";
try {
    $result = $contentHelper->updatePageContent($page_key, $section_key, $title, $content, $_SESSION['user_id'] ?? 1);
    if ($result) {
        echo "<p style='color: green;'>✅ ContentHelper updatePageContent: SUCCESS</p>";
    } else {
        echo "<p style='color: red;'>❌ ContentHelper updatePageContent: FAILED</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ ContentHelper error: " . $e->getMessage() . "</p>";
}

echo "<h2>Session Info:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>🔗 Test Links:</h2>";
echo "<p><a href='" . base_url('arrangor') . "' target='_blank'>Go to Arrangør page</a></p>";
?>
