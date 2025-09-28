<?php
/**
 * Simple Test of Arrangør Page Logic
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Helpers/ViewHelper.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Helpers/InlineEditHelper.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Get editable content
$hero_content = render_editable_content('arrangor', 'hero_title', 'Bli Arrangør for Jaktfeltcup', 'Bli del av Norges største skytekonkurranse som arrangør.');

echo "<h1>🧪 Simple Test of Arrangør Page Logic</h1>";

echo "<h2>Debug Info:</h2>";
echo "<p>can_edit_inline(): " . (can_edit_inline() ? 'TRUE' : 'FALSE') . "</p>";
echo "<p>hero_content['editor_html'] empty: " . (empty($hero_content['editor_html']) ? 'TRUE' : 'FALSE') . "</p>";
echo "<p>hero_content['editor_html'] length: " . strlen($hero_content['editor_html']) . "</p>";

echo "<h2>Conditional Logic:</h2>";
$condition = can_edit_inline() && !empty($hero_content['editor_html']);
echo "<p>can_edit_inline() && !empty(\$hero_content['editor_html']): " . ($condition ? 'TRUE' : 'FALSE') . "</p>";

echo "<h2>What gets rendered (exact same as arrangor/index.php):</h2>";
echo "<div style='border: 2px solid #007bff; padding: 20px; margin: 20px 0; background: #f9f9f9;'>";

// Exact same logic as in arrangor/index.php
if (can_edit_inline() && !empty($hero_content['editor_html'])) {
    echo "<p style='color: green;'>✅ Rendering editor HTML</p>";
    echo $hero_content['editor_html'];
} else {
    echo "<p style='color: red;'>❌ Rendering normal HTML</p>";
    echo "<h1 class='display-4 fw-bold mb-4'>" . htmlspecialchars($hero_content['title']) . "</h1>";
    echo "<p class='lead mb-4'>" . htmlspecialchars($hero_content['content']) . "</p>";
}

echo "</div>";

echo "<h2>🔗 Test Links:</h2>";
echo "<p><a href='" . base_url('arrangor') . "' target='_blank'>Go to real Arrangør page</a></p>";
echo "<p><a href='" . base_url('debug_arrangor_live.php') . "' target='_blank'>Debug Arrangør Live</a></p>";
?>
