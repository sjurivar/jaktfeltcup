<?php
/**
 * Safe Migration Script: Add is_test_data column to all tables
 * Checks if column exists before adding it
 */

echo "<h2>Jaktfeltcup Safe Database Migration</h2>";
echo "<p>Adding is_test_data column to all tables (with safety checks)...</p>";

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'jaktfeltcup';

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to database</p>";
    
    // List of tables to migrate
    $tables = [
        'jaktfelt_users',
        'jaktfelt_seasons', 
        'jaktfelt_competitions',
        'jaktfelt_categories',
        'jaktfelt_competition_categories',
        'jaktfelt_registrations',
        'jaktfelt_results',
        'jaktfelt_point_systems',
        'jaktfelt_point_rules',
        'jaktfelt_season_point_systems',
        'jaktfelt_email_verifications',
        'jaktfelt_notifications',
        'jaktfelt_offline_sync',
        'jaktfelt_audit_log'
    ];
    
    $executedCount = 0;
    
    foreach ($tables as $table) {
        echo "<h3>Processing table: $table</h3>";
        
        try {
            // Check if table exists
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() == 0) {
                echo "<p>⚠️ Table $table does not exist, skipping...</p>";
                continue;
            }
            
            // Check if is_test_data column exists
            $result = $pdo->query("SHOW COLUMNS FROM $table LIKE 'is_test_data'");
            if ($result->rowCount() > 0) {
                echo "<p>✅ Column is_test_data already exists in $table</p>";
            } else {
                // Add the column
                $sql = "ALTER TABLE $table ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE";
                $pdo->exec($sql);
                echo "<p>✅ Added is_test_data column to $table</p>";
                $executedCount++;
            }
            
            // Check if index exists
            $result = $pdo->query("SHOW INDEX FROM $table WHERE Key_name = 'idx_{$table}_test_data'");
            if ($result->rowCount() > 0) {
                echo "<p>✅ Index idx_{$table}_test_data already exists</p>";
            } else {
                // Add the index
                $indexName = 'idx_' . str_replace('jaktfelt_', '', $table) . '_test_data';
                $sql = "CREATE INDEX $indexName ON $table(is_test_data)";
                $pdo->exec($sql);
                echo "<p>✅ Added index $indexName to $table</p>";
                $executedCount++;
            }
            
        } catch (PDOException $e) {
            echo "<p>❌ Error processing $table: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<p><strong>Migration complete!</strong> ($executedCount operations executed)</p>";
    echo "<p>You can now run <a href='setup_sample_data.php'>setup_sample_data.php</a> to import sample data.</p>";
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure the database is set up first by running <a href='setup_database.php'>setup_database.php</a></p>";
}
?>
