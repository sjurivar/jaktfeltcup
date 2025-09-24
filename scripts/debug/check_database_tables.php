<?php
/**
 * Check Database Tables
 * List all tables and their row counts
 */

echo "<h2>Jaktfeltcup - Database Tables Check</h2>";

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
    
    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tables in database:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Table Name</th><th>Row Count</th><th>Test Data Count</th><th>Status</th></tr>";
    
    foreach ($tables as $table) {
        try {
            $totalCount = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
            
            // Check if is_test_data column exists
            $hasTestDataColumn = false;
            try {
                $pdo->query("SELECT is_test_data FROM `$table` LIMIT 1");
                $hasTestDataColumn = true;
            } catch (PDOException $e) {
                // Column doesn't exist
            }
            
            $testDataCount = 0;
            if ($hasTestDataColumn) {
                $testDataCount = $pdo->query("SELECT COUNT(*) FROM `$table` WHERE is_test_data = 1")->fetchColumn();
            }
            
            $status = $hasTestDataColumn ? "✅ Has test_data column" : "❌ Missing test_data column";
            
            echo "<tr>";
            echo "<td>$table</td>";
            echo "<td>$totalCount</td>";
            echo "<td>" . ($hasTestDataColumn ? $testDataCount : "N/A") . "</td>";
            echo "<td>$status</td>";
            echo "</tr>";
            
        } catch (PDOException $e) {
            echo "<tr>";
            echo "<td>$table</td>";
            echo "<td>Error</td>";
            echo "<td>Error</td>";
            echo "<td>❌ " . $e->getMessage() . "</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    // Check for old tables (without prefix)
    $oldTables = [];
    foreach ($tables as $table) {
        if (!str_starts_with($table, 'jaktfelt_')) {
            $oldTables[] = $table;
        }
    }
    
    if (!empty($oldTables)) {
        echo "<h3>⚠️ Old tables found (without jaktfelt_ prefix):</h3>";
        echo "<ul>";
        foreach ($oldTables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
        echo "<p>Consider running <a href='../../admin/database/drop_old_tables.php'>drop_old_tables.php</a> to clean up.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure XAMPP MySQL is running and database '$dbname' exists and credentials are correct.</p>";
}
?>
