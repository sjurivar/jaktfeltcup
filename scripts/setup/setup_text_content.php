<?php
/**
 * Setup Text Content Tables
 * Creates tables for text content management
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

echo "📝 Setting up Text Content Management...\n\n";

try {
    // Initialize database connection
    global $db_config;
    $database = new \Jaktfeltcup\Core\Database($db_config);
    
    echo "✅ Connected to database\n";
    
    // Read and execute text content table SQL
    $sqlFile = __DIR__ . '/../../database/content_text_tables.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $executed = 0;
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            try {
                $database->execute($statement);
                $executed++;
            } catch (Exception $e) {
                // Ignore "table already exists" errors
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "⚠️  Warning: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "✅ Text content tables created/updated ($executed statements executed)\n";
    
    // Verify setup
    $content_count = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_page_content")['count'];
    echo "✅ Found $content_count text content items\n";
    
    // Show available pages
    $pages = $database->queryAll("SELECT DISTINCT page_key FROM jaktfelt_page_content ORDER BY page_key");
    echo "📋 Available pages:\n";
    foreach ($pages as $page) {
        echo "   - " . $page['page_key'] . "\n";
    }
    
    echo "\n🎉 Text content management setup complete!\n";
    echo "\n🌐 Access text management at: http://localhost/jaktfeltcup/admin/content/text\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
