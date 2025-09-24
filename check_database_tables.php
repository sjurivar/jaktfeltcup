<?php
/**
 * Check Database Tables
 * See what tables actually exist in the database
 */

echo "<h2>Jaktfeltcup - Check Database Tables</h2>";

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
    
    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>📋 All Tables in Database:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Check for old vs new table names
    $oldTables = ['competitions', 'users', 'seasons', 'categories', 'results', 'registrations'];
    $newTables = ['jaktfelt_competitions', 'jaktfelt_users', 'jaktfelt_seasons', 'jaktfelt_categories', 'jaktfelt_results', 'jaktfelt_registrations'];
    
    echo "<h3>🔍 Table Name Analysis:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Old Name</th><th>New Name</th><th>Old Exists</th><th>New Exists</th><th>Status</th></tr>";
    
    foreach ($oldTables as $index => $oldTable) {
        $newTable = $newTables[$index];
        $oldExists = in_array($oldTable, $tables);
        $newExists = in_array($newTable, $tables);
        
        $status = '';
        if ($oldExists && $newExists) {
            $status = '⚠️ Both exist';
        } else if ($oldExists && !$newExists) {
            $status = '❌ Only old exists';
        } else if (!$oldExists && $newExists) {
            $status = '✅ Only new exists';
        } else {
            $status = '❌ Neither exists';
        }
        
        echo "<tr>";
        echo "<td>$oldTable</td>";
        echo "<td>$newTable</td>";
        echo "<td>" . ($oldExists ? 'Yes' : 'No') . "</td>";
        echo "<td>" . ($newExists ? 'Yes' : 'No') . "</td>";
        echo "<td>$status</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check foreign key constraints
    echo "<h3>🔗 Foreign Key Constraints:</h3>";
    try {
        $constraints = $pdo->query("
            SELECT 
                TABLE_NAME,
                COLUMN_NAME,
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM 
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE 
                TABLE_SCHEMA = '$dbname' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ORDER BY TABLE_NAME, CONSTRAINT_NAME
        ")->fetchAll();
        
        if (count($constraints) > 0) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>Table</th><th>Column</th><th>Constraint</th><th>References</th></tr>";
            foreach ($constraints as $constraint) {
                echo "<tr>";
                echo "<td>" . $constraint['TABLE_NAME'] . "</td>";
                echo "<td>" . $constraint['COLUMN_NAME'] . "</td>";
                echo "<td>" . $constraint['CONSTRAINT_NAME'] . "</td>";
                echo "<td>" . $constraint['REFERENCED_TABLE_NAME'] . "." . $constraint['REFERENCED_COLUMN_NAME'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No foreign key constraints found</p>";
        }
    } catch (PDOException $e) {
        echo "<p>❌ Error checking constraints: " . $e->getMessage() . "</p>";
    }
    
    // Check if we have data in old tables
    echo "<h3>📊 Data in Old Tables:</h3>";
    foreach ($oldTables as $table) {
        if (in_array($table, $tables)) {
            try {
                $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
                echo "<p>$table: $count records</p>";
            } catch (PDOException $e) {
                echo "<p>$table: Error - " . $e->getMessage() . "</p>";
            }
        }
    }
    
    // Check if we have data in new tables
    echo "<h3>📊 Data in New Tables:</h3>";
    foreach ($newTables as $table) {
        if (in_array($table, $tables)) {
            try {
                $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
                echo "<p>$table: $count records</p>";
            } catch (PDOException $e) {
                echo "<p>$table: Error - " . $e->getMessage() . "</p>";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>
