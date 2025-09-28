<?php
/**
 * Debug Inline Edit System
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Helpers/ViewHelper.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Helpers/InlineEditHelper.php';

echo "<h1>🔍 Debug Inline Edit System</h1>";

// Check session
echo "<h2>Session Info:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red;'>❌ User not logged in</p>";
    exit;
}

echo "<p style='color: green;'>✅ User logged in: " . $_SESSION['user_id'] . "</p>";

// Initialize database
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Check user roles
echo "<h2>User Roles:</h2>";
try {
    $user_roles = $database->queryAll(
        "SELECT r.role_name FROM jaktfelt_user_roles ur
         JOIN jaktfelt_roles r ON ur.role_id = r.id
         WHERE ur.user_id = ?",
        [$_SESSION['user_id']]
    );
    $user_roles = array_column($user_roles, 'role_name');
    
    echo "<pre>";
    print_r($user_roles);
    echo "</pre>";
    
    if (in_array('contentmanager', $user_roles) || in_array('admin', $user_roles)) {
        echo "<p style='color: green;'>✅ User has content management access</p>";
    } else {
        echo "<p style='color: red;'>❌ User does NOT have content management access</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error fetching user roles: " . $e->getMessage() . "</p>";
}

// Test can_edit_inline function
echo "<h2>can_edit_inline() Test:</h2>";
$can_edit = can_edit_inline();
echo "<p>can_edit_inline() returns: " . ($can_edit ? 'TRUE' : 'FALSE') . "</p>";

// Test render_editable_content
echo "<h2>render_editable_content() Test:</h2>";
$test_content = render_editable_content('arrangor', 'hero_title', 'Test Title', 'Test Content');
echo "<pre>";
print_r($test_content);
echo "</pre>";

// Check if editor_html exists
if (isset($test_content['editor_html'])) {
    echo "<p style='color: green;'>✅ editor_html exists</p>";
    echo "<h3>Editor HTML:</h3>";
    echo "<div style='border: 1px solid #ccc; padding: 10px; background: #f9f9f9;'>";
    echo htmlspecialchars($test_content['editor_html']);
    echo "</div>";
} else {
    echo "<p style='color: red;'>❌ editor_html does NOT exist</p>";
}

// Test database connection
echo "<h2>Database Test:</h2>";
try {
    $test_query = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_page_content");
    echo "<p style='color: green;'>✅ Database connection OK. Content records: " . $test_query['count'] . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<h2>🔗 Test Links:</h2>";
echo "<p><a href='" . base_url('arrangor') . "'>Go to Arrangør page</a></p>";
echo "<p><a href='" . base_url('admin/content/text') . "'>Go to Text Content Admin</a></p>";
?>
