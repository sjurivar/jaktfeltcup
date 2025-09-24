<?php
/**
 * Sample Data Setup Script
 * Run this to import sample data for testing
 */

echo "<h2>Jaktfeltcup Sample Data Setup</h2>";

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
    
    // Read and execute sample data
    $sampleData = file_get_contents(__DIR__ . '/database/sample_data.sql');
    if ($sampleData) {
        // Split by semicolon and execute each statement
        $statements = explode(';', $sampleData);
        
        $importedCount = 0;
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                try {
                    $pdo->exec($statement);
                    $importedCount++;
                } catch (PDOException $e) {
                    // Skip duplicate key errors and other non-critical errors
                    if (strpos($e->getMessage(), 'Duplicate entry') === false && 
                        strpos($e->getMessage(), 'Unknown column') === false) {
                        echo "<p>⚠️ Warning: " . $e->getMessage() . "</p>";
                    } else if (strpos($e->getMessage(), 'Unknown column') !== false) {
                        echo "<p>❌ Error: Missing is_test_data column. Please run <a href='migrate_add_test_data_column.php'>migrate_add_test_data_column.php</a> first.</p>";
                        exit;
                    }
                }
            }
        }
        
        echo "<p>✅ Sample data imported successfully ($importedCount statements executed)</p>";
        
        // Show summary
        $tables = [
            'jaktfelt_seasons' => 'Seasons',
            'jaktfelt_categories' => 'Categories', 
            'jaktfelt_users' => 'Users',
            'jaktfelt_competitions' => 'Competitions',
            'jaktfelt_competition_categories' => 'Competition Categories',
            'jaktfelt_registrations' => 'Registrations',
            'jaktfelt_results' => 'Results',
            'jaktfelt_point_systems' => 'Point Systems',
            'jaktfelt_point_rules' => 'Point Rules',
            'jaktfelt_season_point_systems' => 'Season Point Systems',
            'jaktfelt_notifications' => 'Notifications',
            'jaktfelt_audit_log' => 'Audit Log'
        ];
        
        echo "<h3>Sample Data Summary:</h3>";
        echo "<ul>";
        foreach ($tables as $table => $name) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table WHERE is_test_data = TRUE")->fetchColumn();
            echo "<li><strong>$name:</strong> $count test records</li>";
        }
        echo "</ul>";
        
        echo "<h3>Test Users:</h3>";
        echo "<ul>";
        echo "<li><strong>Admin:</strong> testadmin / password</li>";
        echo "<li><strong>Organizer:</strong> testorganizer / password</li>";
        echo "<li><strong>Participants:</strong> testdeltaker1-10 / password</li>";
        echo "</ul>";
        
        echo "<p><strong>Sample data setup complete!</strong></p>";
        echo "<p><a href='/jaktfeltcup/'>Go to Jaktfeltcup</a></p>";
        
    } else {
        echo "<p>❌ Could not read sample data file</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure the database is set up first by running <a href='setup_database.php'>setup_database.php</a></p>";
}
?>
