<?php
/**
 * Test Arrangør Rendering
 */

session_start();

// Set page variables
$page_title = 'Test Arrangør - Jaktfeltcup';
$page_description = 'Test version of arrangør page.';
$current_page = 'arrangor';

// Include required files
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Helpers/ViewHelper.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Helpers/InlineEditHelper.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Get editable content
$hero_content = render_editable_content('arrangor', 'hero_title', 'Bli Arrangør for Jaktfeltcup', 'Bli del av Norges største skytekonkurranse som arrangør.');
$benefits_content = render_editable_content('arrangor', 'benefits_title', 'Hvorfor bli arrangør?', 'Som arrangør for Jaktfeltcup får du mulighet til å bidra til utvikling av skyteidretten og skape verdifulle opplevelser for deltakere.');

echo "<h1>🧪 Test Arrangør Rendering</h1>";

echo "<h2>Debug Info:</h2>";
echo "<p>can_edit_inline(): " . (can_edit_inline() ? 'TRUE' : 'FALSE') . "</p>";
echo "<p>hero_content['editor_html'] empty: " . (empty($hero_content['editor_html']) ? 'TRUE' : 'FALSE') . "</p>";
echo "<p>hero_content['editor_html'] length: " . strlen($hero_content['editor_html']) . "</p>";

echo "<h2>Conditional Logic Test:</h2>";
$condition = can_edit_inline() && !empty($hero_content['editor_html']);
echo "<p>can_edit_inline() && !empty(\$hero_content['editor_html']): " . ($condition ? 'TRUE' : 'FALSE') . "</p>";

echo "<h2>Hero Section (exact same logic as arrangor/index.php):</h2>";
echo "<div style='border: 2px solid #007bff; padding: 20px; margin: 20px 0; background: #f9f9f9;'>";
echo "<h3>Hero Section</h3>";
echo "<div class='container'>";
echo "<div class='row align-items-center'>";
echo "<div class='col-lg-8'>";

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
echo "</div>";
echo "</div>";
echo "</div>";

echo "<h2>🔗 Test Links:</h2>";
echo "<p><a href='" . base_url('arrangor') . "' target='_blank'>Go to real Arrangør page</a></p>";
echo "<p><a href='" . base_url('debug_arrangor_live.php') . "' target='_blank'>Debug Arrangør Live</a></p>";
?>
