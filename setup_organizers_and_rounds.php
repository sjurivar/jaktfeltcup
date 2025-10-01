<?php
/**
 * Setup organizers and rounds tables and sample data
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Core/Database.php';

global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "<h1>Setup Arrangører og Runder</h1>\n";
echo "<hr>\n";

// Read and execute SQL file
$sqlFile = __DIR__ . '/database/add_rounds_and_organizers.sql';

if (!file_exists($sqlFile)) {
    echo "<p style='color: red;'>❌ SQL-fil ikke funnet: $sqlFile</p>\n";
    exit;
}

$sql = file_get_contents($sqlFile);

echo "<h2>Oppretter tabeller og data...</h2>\n";

try {
    // Split SQL into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^--/', $stmt);
        }
    );
    
    foreach ($statements as $statement) {
        if (empty(trim($statement))) continue;
        
        // Skip comments
        if (preg_match('/^--/', $statement)) continue;
        
        try {
            $database->execute($statement);
            echo "<p style='color: green;'>✅ Utført: " . substr($statement, 0, 50) . "...</p>\n";
        } catch (Exception $e) {
            // Some statements might fail if tables already exist, that's OK
            echo "<p style='color: orange;'>⚠️ " . $e->getMessage() . "</p>\n";
        }
    }
    
    echo "<hr>\n";
    echo "<h2>✅ Setup fullført!</h2>\n";
    
    // Verify data
    echo "<h3>Verifisering:</h3>\n";
    
    $seasonCount = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_seasons WHERE is_active = 1");
    echo "<p><strong>Aktive sesonger:</strong> " . $seasonCount['count'] . "</p>\n";
    
    $roundCount = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_rounds");
    echo "<p><strong>Runder:</strong> " . $roundCount['count'] . "</p>\n";
    
    $organizerCount = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_organizers WHERE is_active = 1");
    echo "<p><strong>Aktive arrangører:</strong> " . $organizerCount['count'] . "</p>\n";
    
    echo "<hr>\n";
    echo "<p><a href='" . base_url('arrangor') . "' class='btn btn-primary'>Se arrangør-siden</a></p>\n";
    echo "<p><a href='" . base_url('admin/content/organizers') . "' class='btn btn-success'>Administrer arrangører</a></p>\n";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil: " . $e->getMessage() . "</p>\n";
}
?>

