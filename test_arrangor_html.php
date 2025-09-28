<?php
/**
 * Test Arrangør HTML Rendering
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Helpers/ViewHelper.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Helpers/InlineEditHelper.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Get editable content (same as in arrangor/index.php)
$hero_content = render_editable_content('arrangor', 'hero_title', 'Bli Arrangør for Jaktfeltcup', 'Bli del av Norges største skytekonkurranse som arrangør.');

echo "<h1>🧪 Test Arrangør HTML Rendering</h1>";

echo "<h2>Raw editor_html:</h2>";
echo "<pre>";
echo htmlspecialchars($hero_content['editor_html']);
echo "</pre>";

echo "<h2>Rendered editor_html:</h2>";
echo "<div style='border: 2px solid #007bff; padding: 20px; margin: 20px 0; background: #f9f9f9;'>";
echo $hero_content['editor_html'];
echo "</div>";

echo "<h2>Conditional Logic Test:</h2>";
$condition = can_edit_inline() && !empty($hero_content['editor_html']);
echo "<p>can_edit_inline() && !empty(\$hero_content['editor_html']): " . ($condition ? 'TRUE' : 'FALSE') . "</p>";

echo "<h2>What should be rendered:</h2>";
if ($condition) {
    echo "<p style='color: green;'>✅ Should render editor HTML</p>";
    echo "<div style='border: 2px solid #28a745; padding: 20px; margin: 20px 0; background: #f0f8f0;'>";
    echo $hero_content['editor_html'];
    echo "</div>";
} else {
    echo "<p style='color: red;'>❌ Should render normal HTML</p>";
    echo "<div style='border: 2px solid #dc3545; padding: 20px; margin: 20px 0; background: #f8f0f0;'>";
    echo "<h1 class='display-4 fw-bold mb-4'>" . htmlspecialchars($hero_content['title']) . "</h1>";
    echo "<p class='lead mb-4'>" . htmlspecialchars($hero_content['content']) . "</p>";
    echo "</div>";
}

echo "<h2>🔗 Test Links:</h2>";
echo "<p><a href='" . base_url('arrangor') . "' target='_blank'>Go to Arrangør page</a></p>";
echo "<p><a href='" . base_url('debug_arrangor_page.php') . "' target='_blank'>Debug Arrangør Page</a></p>";
?>
