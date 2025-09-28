<?php
/**
 * Debug Arrangør Page
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Helpers/ViewHelper.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Helpers/InlineEditHelper.php';

echo "<h1>🔍 Debug Arrangør Page</h1>";

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Get editable content (same as in arrangor/index.php)
$hero_content = render_editable_content('arrangor', 'hero_title', 'Bli Arrangør for Jaktfeltcup', 'Bli del av Norges største skytekonkurranse som arrangør.');
$benefits_content = render_editable_content('arrangor', 'benefits_title', 'Hvorfor bli arrangør?', 'Som arrangør for Jaktfeltcup får du mulighet til å bidra til utvikling av skyteidretten og skape verdifulle opplevelser for deltakere.');

echo "<h2>Hero Content Debug:</h2>";
echo "<pre>";
print_r($hero_content);
echo "</pre>";

echo "<h2>Benefits Content Debug:</h2>";
echo "<pre>";
print_r($benefits_content);
echo "</pre>";

echo "<h2>can_edit_inline() Test:</h2>";
echo "<p>can_edit_inline(): " . (can_edit_inline() ? 'TRUE' : 'FALSE') . "</p>";

echo "<h2>Conditional Logic Test:</h2>";
echo "<p>can_edit_inline() && !empty(\$hero_content['editor_html']): " . ((can_edit_inline() && !empty($hero_content['editor_html'])) ? 'TRUE' : 'FALSE') . "</p>";

echo "<h2>Hero Content HTML Test:</h2>";
if (can_edit_inline() && !empty($hero_content['editor_html'])) {
    echo "<p style='color: green;'>✅ Should render editor HTML</p>";
    echo "<div style='border: 2px solid #007bff; padding: 20px; margin: 20px 0; background: #f9f9f9;'>";
    echo $hero_content['editor_html'];
    echo "</div>";
} else {
    echo "<p style='color: red;'>❌ Should render normal HTML</p>";
    echo "<div style='border: 2px solid #28a745; padding: 20px; margin: 20px 0; background: #f0f8f0;'>";
    echo "<h1 class='display-4 fw-bold mb-4'>" . htmlspecialchars($hero_content['title']) . "</h1>";
    echo "<p class='lead mb-4'>" . htmlspecialchars($hero_content['content']) . "</p>";
    echo "</div>";
}

echo "<h2>Benefits Content HTML Test:</h2>";
if (can_edit_inline() && !empty($benefits_content['editor_html'])) {
    echo "<p style='color: green;'>✅ Should render editor HTML</p>";
    echo "<div style='border: 2px solid #007bff; padding: 20px; margin: 20px 0; background: #f9f9f9;'>";
    echo $benefits_content['editor_html'];
    echo "</div>";
} else {
    echo "<p style='color: red;'>❌ Should render normal HTML</p>";
    echo "<div style='border: 2px solid #28a745; padding: 20px; margin: 20px 0; background: #f0f8f0;'>";
    echo "<h2 class='mb-4'>" . htmlspecialchars($benefits_content['title']) . "</h2>";
    echo "<p class='lead text-muted mb-5'>" . htmlspecialchars($benefits_content['content']) . "</p>";
    echo "</div>";
}
?>
