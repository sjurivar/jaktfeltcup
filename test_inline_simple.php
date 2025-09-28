<?php
/**
 * Simple Inline Edit Test
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Helpers/ViewHelper.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Helpers/InlineEditHelper.php';

// Initialize database
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "<h1>🧪 Simple Inline Edit Test</h1>";

// Test 1: Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red;'>❌ Not logged in. <a href='" . base_url('login') . "'>Login here</a></p>";
    exit;
}

echo "<p style='color: green;'>✅ Logged in as user ID: " . $_SESSION['user_id'] . "</p>";

// Test 2: Check can_edit_inline
$can_edit = can_edit_inline();
echo "<p>can_edit_inline(): " . ($can_edit ? '✅ TRUE' : '❌ FALSE') . "</p>";

// Test 3: Test render_editable_content
$test_content = render_editable_content('arrangor', 'hero_title', 'Test Title', 'Test Content');
echo "<h2>Test Content Result:</h2>";
echo "<pre>";
print_r($test_content);
echo "</pre>";

// Test 4: Show the actual page
echo "<h2>Live Test:</h2>";
echo "<p>Go to: <a href='" . base_url('arrangor') . "' target='_blank'>" . base_url('arrangor') . "</a></p>";

// Test 5: Manual inline edit test
if ($can_edit) {
    echo "<h2>Manual Inline Edit Test:</h2>";
    echo "<div style='border: 2px solid #007bff; padding: 20px; margin: 20px 0;'>";
    echo $test_content['editor_html'];
    echo "</div>";
} else {
    echo "<h2>❌ Cannot edit - check user roles</h2>";
    echo "<p>Make sure you have 'contentmanager' or 'admin' role assigned.</p>";
}
?>
