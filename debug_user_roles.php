<?php
/**
 * Debug user roles
 */

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Core/Database.php';

global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "<h1>Debug User Roles</h1>\n";
echo "<hr>\n";

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red;'>❌ Ikke innlogget</p>\n";
    exit;
}

echo "<h2>Session info:</h2>\n";
echo "<ul>\n";
echo "<li><strong>User ID:</strong> " . $_SESSION['user_id'] . "</li>\n";
echo "<li><strong>Username:</strong> " . ($_SESSION['username'] ?? 'N/A') . "</li>\n";
echo "<li><strong>Name:</strong> " . ($_SESSION['user_name'] ?? 'N/A') . "</li>\n";
echo "</ul>\n";

echo "<hr>\n";

// Get user roles
echo "<h2>User Roles:</h2>\n";

try {
    $user_roles = $database->queryAll(
        "SELECT r.id, r.role_name, r.description 
         FROM jaktfelt_user_roles ur 
         JOIN jaktfelt_roles r ON ur.role_id = r.id 
         WHERE ur.user_id = ?", 
        [$_SESSION['user_id']]
    );
    
    if (!empty($user_roles)) {
        echo "<ul>\n";
        foreach ($user_roles as $role) {
            echo "<li><strong>" . htmlspecialchars($role['role_name']) . "</strong> - " . htmlspecialchars($role['description']) . "</li>\n";
        }
        echo "</ul>\n";
        
        // Check session roles
        echo "<h3>Session roles:</h3>\n";
        if (isset($_SESSION['roles'])) {
            echo "<pre>" . print_r($_SESSION['roles'], true) . "</pre>\n";
        } else {
            echo "<p style='color: orange;'>⚠️ Ingen roller i session - dette kan være problemet!</p>\n";
        }
    } else {
        echo "<p style='color: red;'>❌ Ingen roller funnet for brukeren</p>\n";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}

echo "<hr>\n";

// Check access for content management
echo "<h2>Tilgangskontroll:</h2>\n";

$role_names = array_column($user_roles, 'role_name');
echo "<p><strong>Roller:</strong> " . implode(', ', $role_names) . "</p>\n";

if (in_array('contentmanager', $role_names)) {
    echo "<p style='color: green;'>✅ Har contentmanager rolle</p>\n";
} else {
    echo "<p style='color: red;'>❌ Mangler contentmanager rolle</p>\n";
}

if (in_array('databasemanager', $role_names)) {
    echo "<p style='color: green;'>✅ Har databasemanager rolle</p>\n";
} else {
    echo "<p style='color: orange;'>⚠️ Mangler databasemanager rolle</p>\n";
}

if (in_array('admin', $role_names)) {
    echo "<p style='color: green;'>✅ Har admin rolle</p>\n";
} else {
    echo "<p style='color: orange;'>⚠️ Mangler admin rolle</p>\n";
}

echo "<hr>\n";
echo "<p><a href='/jaktfeltcup/admin/content'>Tilbake til content management</a></p>\n";
?>

