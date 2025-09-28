<?php
/**
 * Setup Content Tables
 * Creates the necessary tables for content management
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "<h1>Content Tables Setup</h1>";

try {
    // Read and execute content_tables.sql
    $sqlFile = __DIR__ . '/../../database/content_tables.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $database->execute($statement);
            echo "<p>✅ Executed: " . substr($statement, 0, 50) . "...</p>";
            $successCount++;
        } catch (Exception $e) {
            echo "<p>❌ Error: " . $e->getMessage() . "</p>";
            echo "<p>Statement: " . htmlspecialchars(substr($statement, 0, 100)) . "...</p>";
            $errorCount++;
        }
    }
    
    echo "<h2>Summary</h2>";
    echo "<p>✅ Successful: $successCount</p>";
    echo "<p>❌ Errors: $errorCount</p>";
    
    // Test the tables
    echo "<h2>Testing Tables</h2>";
    
    try {
        $newsCount = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_news")['count'];
        echo "<p>📰 News items: $newsCount</p>";
    } catch (Exception $e) {
        echo "<p>❌ News table error: " . $e->getMessage() . "</p>";
    }
    
    try {
        $sponsorCount = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_sponsors")['count'];
        echo "<p>🤝 Sponsors: $sponsorCount</p>";
    } catch (Exception $e) {
        echo "<p>❌ Sponsors table error: " . $e->getMessage() . "</p>";
    }
    
    echo "<p><a href='index.php'>Go to Content Management</a></p>";
    
} catch (Exception $e) {
    echo "<p>❌ Setup failed: " . $e->getMessage() . "</p>";
}
?>
