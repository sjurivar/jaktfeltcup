<?php
/**
 * Migration Script: Add is_test_data column to all tables
 * Run this if you have an existing database without the is_test_data column
 */

echo "<h2>Jaktfeltcup Database Migration</h2>";
echo "<p>Adding is_test_data column to all tables...</p>";

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
    
    // Read and execute migration
    $migration = file_get_contents(__DIR__ . '/database/migration_add_test_data_column.sql');
    if ($migration) {
        // Split by semicolon and execute each statement
        $statements = explode(';', $migration);
        
        $executedCount = 0;
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                try {
                    $pdo->exec($statement);
                    $executedCount++;
                    echo "<p>✅ Executed: " . substr($statement, 0, 50) . "...</p>";
                } catch (PDOException $e) {
                    // Skip if column already exists
                    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                        echo "<p>⚠️ Column already exists: " . substr($statement, 0, 50) . "...</p>";
                    } else if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
                        echo "<p>⚠️ Index already exists: " . substr($statement, 0, 50) . "...</p>";
                    } else {
                        echo "<p>❌ Error: " . $e->getMessage() . "</p>";
                        echo "<p>Statement: " . htmlspecialchars($statement) . "</p>";
                    }
                }
            }
        }
        
        echo "<p><strong>Migration complete!</strong> ($executedCount statements executed)</p>";
        echo "<p>You can now run <a href='setup_sample_data.php'>setup_sample_data.php</a> to import sample data.</p>";
        
    } else {
        echo "<p>❌ Could not read migration file</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure the database is set up first by running <a href='setup_database.php'>setup_database.php</a></p>";
}
?>
