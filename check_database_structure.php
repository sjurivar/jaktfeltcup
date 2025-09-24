<?php
/**
 * Check Database Structure
 * Verify if is_test_data columns exist in all tables
 */

echo "<h2>Jaktfeltcup Database Structure Check</h2>";

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
    
    echo "<h3>Table Structure Check:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Table</th><th>is_test_data Column</th><th>Status</th></tr>";
    
    $allGood = true;
    
    foreach ($tables as $table) {
        try {
            // Check if table exists
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() == 0) {
                echo "<tr><td>$table</td><td>N/A</td><td style='color: red;'>❌ Table does not exist</td></tr>";
                $allGood = false;
                continue;
            }
            
            // Check if is_test_data column exists
            $result = $pdo->query("SHOW COLUMNS FROM $table LIKE 'is_test_data'");
            if ($result->rowCount() > 0) {
                echo "<tr><td>$table</td><td>✅ Exists</td><td style='color: green;'>✅ OK</td></tr>";
            } else {
                echo "<tr><td>$table</td><td>❌ Missing</td><td style='color: red;'>❌ Needs migration</td></tr>";
                $allGood = false;
            }
            
        } catch (PDOException $e) {
            echo "<tr><td>$table</td><td>Error</td><td style='color: red;'>❌ " . $e->getMessage() . "</td></tr>";
            $allGood = false;
        }
    }
    
    echo "</table>";
    
    if ($allGood) {
        echo "<p style='color: green; font-weight: bold;'>✅ All tables have the is_test_data column!</p>";
        echo "<p>You can now run <a href='setup_sample_data.php'>setup_sample_data.php</a> to import sample data.</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Some tables are missing the is_test_data column.</p>";
        echo "<p>Please run <a href='migrate_add_test_data_column.php'>migrate_add_test_data_column.php</a> to add the missing columns.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure the database is set up first by running <a href='setup_database.php'>setup_database.php</a></p>";
}
?>
