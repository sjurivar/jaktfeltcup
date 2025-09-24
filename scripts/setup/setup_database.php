<?php
/**
 * Setup Database
 * Create database and import schema
 */

echo "<h2>Jaktfeltcup Database Setup</h2>";

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'jaktfeltcup';

try {
    // Connect to MySQL (without database)
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to MySQL server</p>";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p>✅ Database '$dbname' created or already exists</p>";
    
    // Use the database
    $pdo->exec("USE `$dbname`");
    
    // Read and execute schema
    $schemaFile = __DIR__ . '/../../database/schema.sql';
    $schema = file_get_contents($schemaFile);
    
    if ($schema) {
        // Disable foreign key checks during setup
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        $statements = explode(';', $schema);
        $executedCount = 0;
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^--/', $statement)) {
                try {
                    $pdo->exec($statement);
                    $executedCount++;
                } catch (PDOException $e) {
                    if (strpos($e->getMessage(), 'already exists') === false) {
                        echo "<p>⚠️ Warning: " . $e->getMessage() . "</p>";
                    }
                }
            }
        }
        
        // Re-enable foreign key checks
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        echo "<p>✅ Schema imported successfully ($executedCount statements executed)</p>";
        echo "<p>Database setup complete! You can now:</p>";
        echo "<ul>";
        echo "<li><a href='../migration/check_database_structure.php'>Check database structure</a></li>";
        echo "<li><a href='setup_sample_data.php'>Import sample data</a></li>";
        echo "<li><a href='../../admin/database/'>Go to database admin panel</a></li>";
        echo "</ul>";
        
    } else {
        echo "<p>❌ Could not read schema file: $schemaFile</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure XAMPP MySQL is running and credentials are correct.</p>";
}
?>
