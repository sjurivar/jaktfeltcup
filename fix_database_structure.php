<?php
/**
 * Fix Database Structure
 * Clean up any partial migrations and ensure consistent structure
 */

echo "<h2>Jaktfeltcup Database Structure Fix</h2>";
echo "<p>Cleaning up database structure and ensuring consistency...</p>";

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
    
    // List of tables to check
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
    
    $fixedCount = 0;
    
    foreach ($tables as $table) {
        echo "<h3>Checking table: $table</h3>";
        
        try {
            // Check if table exists
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() == 0) {
                echo "<p>⚠️ Table $table does not exist, skipping...</p>";
                continue;
            }
            
            // Check if is_test_data column exists
            $result = $pdo->query("SHOW COLUMNS FROM $table LIKE 'is_test_data'");
            if ($result->rowCount() == 0) {
                // Add the column
                $sql = "ALTER TABLE $table ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE";
                $pdo->exec($sql);
                echo "<p>✅ Added is_test_data column to $table</p>";
                $fixedCount++;
            } else {
                echo "<p>✅ Column is_test_data already exists in $table</p>";
            }
            
            // Check for any problematic indexes and remove them
            $result = $pdo->query("SHOW INDEX FROM $table WHERE Key_name = 'idx_test_data'");
            if ($result->rowCount() > 0) {
                // Remove the problematic index
                $sql = "DROP INDEX idx_test_data ON $table";
                $pdo->exec($sql);
                echo "<p>✅ Removed problematic index idx_test_data from $table</p>";
                $fixedCount++;
            }
            
            // Add the correct index
            $indexName = 'idx_' . str_replace('jaktfelt_', '', $table) . '_test_data';
            $result = $pdo->query("SHOW INDEX FROM $table WHERE Key_name = '$indexName'");
            if ($result->rowCount() == 0) {
                $sql = "CREATE INDEX $indexName ON $table(is_test_data)";
                $pdo->exec($sql);
                echo "<p>✅ Added correct index $indexName to $table</p>";
                $fixedCount++;
            } else {
                echo "<p>✅ Index $indexName already exists in $table</p>";
            }
            
        } catch (PDOException $e) {
            echo "<p>❌ Error processing $table: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<p><strong>Database structure fix complete!</strong> ($fixedCount operations executed)</p>";
    echo "<p>You can now run <a href='check_database_structure.php'>check_database_structure.php</a> to verify the structure.</p>";
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure the database is set up first by running <a href='setup_database.php'>setup_database.php</a></p>";
}
?>
