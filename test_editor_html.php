<?php
/**
 * Test Editor HTML Output
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Helpers/ViewHelper.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Helpers/InlineEditHelper.php';

echo "<h1>🧪 Test Editor HTML Output</h1>";

// Test render_editable_content
$test_content = render_editable_content('arrangor', 'hero_title', 'Test Title', 'Test Content');

echo "<h2>Raw Output:</h2>";
echo "<pre>";
print_r($test_content);
echo "</pre>";

echo "<h2>Editor HTML (Raw):</h2>";
echo "<pre>";
echo htmlspecialchars($test_content['editor_html']);
echo "</pre>";

echo "<h2>Editor HTML (Rendered):</h2>";
echo "<div style='border: 2px solid #007bff; padding: 20px; margin: 20px 0; background: #f9f9f9;'>";
echo $test_content['editor_html'];
echo "</div>";

echo "<h2>Debug Info:</h2>";
echo "<p>can_edit_inline(): " . (can_edit_inline() ? 'TRUE' : 'FALSE') . "</p>";
echo "<p>editor_html empty: " . (empty($test_content['editor_html']) ? 'TRUE' : 'FALSE') . "</p>";
echo "<p>editor_html length: " . strlen($test_content['editor_html']) . "</p>";
?>
