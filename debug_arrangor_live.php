<?php
/**
 * Debug Arrangør Live Page
 */

session_start();

// Set page variables
$page_title = 'Debug Arrangør - Jaktfeltcup';
$page_description = 'Debug version of arrangør page.';
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

echo "<h1>🔍 Debug Arrangør Live Page</h1>";

echo "<h2>Debug Info:</h2>";
echo "<p>can_edit_inline(): " . (can_edit_inline() ? 'TRUE' : 'FALSE') . "</p>";
echo "<p>hero_content['editor_html'] empty: " . (empty($hero_content['editor_html']) ? 'TRUE' : 'FALSE') . "</p>";
echo "<p>hero_content['editor_html'] length: " . strlen($hero_content['editor_html']) . "</p>";

echo "<h2>Hero Content:</h2>";
echo "<pre>";
print_r($hero_content);
echo "</pre>";

echo "<h2>Conditional Logic:</h2>";
$condition = can_edit_inline() && !empty($hero_content['editor_html']);
echo "<p>can_edit_inline() && !empty(\$hero_content['editor_html']): " . ($condition ? 'TRUE' : 'FALSE') . "</p>";

echo "<h2>What gets rendered:</h2>";
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
echo "<p><a href='" . base_url('arrangor') . "' target='_blank'>Go to real Arrangør page</a></p>";
?>
