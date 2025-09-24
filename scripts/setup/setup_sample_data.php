<?php
/**
 * Setup Sample Data
 * Import sample data into database
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
    
    echo "<p>✅ Connected to database '$dbname'</p>";
    
    // Read sample data SQL file
    $sampleDataFile = __DIR__ . '/../../database/sample_data.sql';
    $sampleData = file_get_contents($sampleDataFile);
    
    if ($sampleData) {
        $statements = explode(';', $sampleData);
        $importedCount = 0;
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                try {
                    $pdo->exec($statement);
                    $importedCount++;
                } catch (PDOException $e) {
                    if (strpos($e->getMessage(), 'Duplicate entry') === false &&
                        strpos($e->getMessage(), 'Unknown column') === false) {
                        echo "<p>⚠️ Warning: " . $e->getMessage() . "</p>";
                    } else if (strpos($e->getMessage(), 'Unknown column') !== false) {
                        echo "<p>❌ Error: Missing is_test_data column. Please run <a href='../migration/migrate_add_test_data_column.php'>migrate_add_test_data_column.php</a> first.</p>";
                        exit;
                    }
                }
            }
        }
        
        echo "<p>✅ Sample data imported successfully ($importedCount statements executed)</p>";
        
        // Show summary
        $tables = ['jaktfelt_users', 'jaktfelt_seasons', 'jaktfelt_competitions', 'jaktfelt_results'];
        echo "<h3>Data Summary:</h3>";
        echo "<ul>";
        foreach ($tables as $table) {
            try {
                $count = $pdo->query("SELECT COUNT(*) FROM $table WHERE is_test_data = 1")->fetchColumn();
                echo "<li>$table: $count test records</li>";
            } catch (PDOException $e) {
                echo "<li>$table: Error counting records</li>";
            }
        }
        echo "</ul>";
        
        echo "<p><a href='../../admin/database/'>Go to database admin panel</a></p>";
        
    } else {
        echo "<p>❌ Could not read sample data file: $sampleDataFile</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure XAMPP MySQL is running and database '$dbname' exists and credentials are correct.</p>";
}
?>
