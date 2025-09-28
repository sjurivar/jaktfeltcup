<?php
/**
 * Fix Text Content Table
 * Fixes the unique constraint issue in jaktfelt_page_content table
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

echo "🔧 Fixing Text Content Table...\n\n";

try {
    // Initialize database connection
    global $db_config;
    $database = new \Jaktfeltcup\Core\Database($db_config);
    
    echo "✅ Connected to database\n";
    
    // Check if table exists
    $table_exists = $database->queryOne("SHOW TABLES LIKE 'jaktfelt_page_content'");
    
    if ($table_exists) {
        echo "📝 Table exists - checking structure...\n";
        
        // Check if the problematic unique constraint exists
        $constraints = $database->queryAll("SHOW INDEX FROM jaktfelt_page_content WHERE Key_name = 'page_key'");
        
        if (!empty($constraints)) {
            echo "⚠️  Found problematic unique constraint on page_key\n";
            echo "🔧 Dropping problematic constraint...\n";
            
            // Drop the problematic unique constraint
            $database->execute("ALTER TABLE jaktfelt_page_content DROP INDEX page_key");
            echo "✅ Dropped problematic constraint\n";
        }
        
        // Add the correct unique constraint
        echo "🔧 Adding correct unique constraint...\n";
        try {
            $database->execute("ALTER TABLE jaktfelt_page_content ADD UNIQUE KEY unique_page_section (page_key, section_key)");
            echo "✅ Added correct unique constraint\n";
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
                echo "ℹ️  Correct constraint already exists\n";
            } else {
                throw $e;
            }
        }
        
    } else {
        echo "📝 Table doesn't exist - creating new table...\n";
        
        // Create the table with correct structure
        $sql = file_get_contents(__DIR__ . '/../../database/content_text_tables.sql');
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        $executed = 0;
        foreach ($statements as $statement) {
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                try {
                    $database->execute($statement);
                    $executed++;
                } catch (Exception $e) {
                    if (strpos($e->getMessage(), 'already exists') === false) {
                        throw $e;
                    }
                }
            }
        }
        
        echo "✅ Table created with correct structure ($executed statements executed)\n";
    }
    
    // Verify the fix
    $constraints = $database->queryAll("SHOW INDEX FROM jaktfelt_page_content WHERE Key_name = 'unique_page_section'");
    if (!empty($constraints)) {
        echo "✅ Correct unique constraint is in place\n";
    } else {
        echo "❌ Unique constraint not found\n";
    }
    
    echo "\n🎉 Text content table fixed!\n";
    echo "🌐 You can now access: http://localhost/jaktfeltcup/admin/content/text\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
