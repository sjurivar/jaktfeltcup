<?php
/**
 * Direct Test of ContentHelper
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Helpers/ContentHelper.php';

echo "<h1>🧪 Direct Test of ContentHelper</h1>";

// Initialize database
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);
$contentHelper = new \Jaktfeltcup\Helpers\ContentHelper($database);

// Test parameters
$page_key = 'arrangor';
$section_key = 'hero_title';
$title = 'Test Title Direct';
$content = 'Test Content Direct';
$user_id = $_SESSION['user_id'] ?? 1;

echo "<h2>Test Parameters:</h2>";
echo "<p>page_key: $page_key</p>";
echo "<p>section_key: $section_key</p>";
echo "<p>title: $title</p>";
echo "<p>content: $content</p>";
echo "<p>user_id: $user_id</p>";

echo "<h2>Database Connection Test:</h2>";
try {
    $test_query = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_page_content");
    echo "<p style='color: green;'>✅ Database connection OK. Content records: " . $test_query['count'] . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<h2>ContentHelper Test:</h2>";
try {
    $result = $contentHelper->updatePageContent($page_key, $section_key, $title, $content, $user_id);
    if ($result) {
        echo "<p style='color: green;'>✅ ContentHelper updatePageContent: SUCCESS</p>";
    } else {
        echo "<p style='color: red;'>❌ ContentHelper updatePageContent: FAILED</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ ContentHelper error: " . $e->getMessage() . "</p>";
}

echo "<h2>Check if record was created/updated:</h2>";
try {
    $check_query = $database->queryOne(
        "SELECT * FROM jaktfelt_page_content WHERE page_key = ? AND section_key = ?",
        [$page_key, $section_key]
    );
    if ($check_query) {
        echo "<p style='color: green;'>✅ Record found in database:</p>";
        echo "<pre>";
        print_r($check_query);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ No record found in database</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking record: " . $e->getMessage() . "</p>";
}

echo "<h2>🔗 Test Links:</h2>";
echo "<p><a href='/jaktfeltcup/arrangor' target='_blank'>Go to Arrangør page</a></p>";
?>
