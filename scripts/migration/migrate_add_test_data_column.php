<?php
/**
 * Migration: Add is_test_data column to all tables
 * This script adds the is_test_data column to existing tables
 */

echo "<h2>Jaktfeltcup - Add is_test_data Column Migration</h2>";

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'jaktfeltcup';

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to database '$dbname'.</p>";
    
    // Read migration SQL file
    $migrationFile = __DIR__ . '/../../database/migration_add_test_data_column.sql';
    $migration = file_get_contents($migrationFile);
    
    if ($migration) {
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
        echo "<p>You can now run <a href='../setup/setup_sample_data.php'>setup_sample_data.php</a> to import sample data.</p>";
        
    } else {
        echo "<p>❌ Could not read migration file: $migrationFile</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure XAMPP MySQL is running and database '$dbname' exists and credentials are correct.</p>";
}
?>
