<?php
/**
 * Fix Foreign Key Constraints
 * Update foreign key constraints to use correct table names
 */

echo "<h2>Jaktfeltcup - Fix Foreign Key Constraints</h2>";

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
    
    // Check if we have old tables (without prefix)
    $oldTables = ['competitions', 'users', 'seasons', 'categories', 'results', 'registrations'];
    $hasOldTables = false;
    foreach ($oldTables as $oldTable) {
        if (in_array($oldTable, $tables)) {
            $hasOldTables = true;
            break;
        }
    }
    
    if ($hasOldTables) {
        echo "<p>⚠️ Found old tables without prefix. You need to recreate the database with the new schema.</p>";
        echo "<p>💡 Run <a href='setup_database.php'>setup_database.php</a> to recreate with correct table names.</p>";
        exit;
    }
    
    // Check if we have new tables (with prefix)
    $newTables = ['jaktfelt_competitions', 'jaktfelt_users', 'jaktfelt_seasons', 'jaktfelt_categories', 'jaktfelt_results', 'jaktfelt_registrations'];
    $hasNewTables = true;
    foreach ($newTables as $newTable) {
        if (!in_array($newTable, $tables)) {
            $hasNewTables = false;
            echo "<p>❌ Missing table: $newTable</p>";
        }
    }
    
    if (!$hasNewTables) {
        echo "<p>❌ Not all new tables exist. Please run <a href='setup_database.php'>setup_database.php</a> first.</p>";
        exit;
    }
    
    echo "<p>✅ All new tables exist</p>";
    
    // Get current foreign key constraints
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
    
    echo "<h3>🔗 Current Foreign Key Constraints:</h3>";
    if (count($constraints) > 0) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Table</th><th>Column</th><th>Constraint</th><th>References</th><th>Status</th></tr>";
        foreach ($constraints as $constraint) {
            $status = '';
            if (strpos($constraint['REFERENCED_TABLE_NAME'], 'jaktfelt_') === 0) {
                $status = '✅ Correct';
            } else {
                $status = '❌ Needs fixing';
            }
            
            echo "<tr>";
            echo "<td>" . $constraint['TABLE_NAME'] . "</td>";
            echo "<td>" . $constraint['COLUMN_NAME'] . "</td>";
            echo "<td>" . $constraint['CONSTRAINT_NAME'] . "</td>";
            echo "<td>" . $constraint['REFERENCED_TABLE_NAME'] . "." . $constraint['REFERENCED_COLUMN_NAME'] . "</td>";
            echo "<td>$status</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No foreign key constraints found</p>";
    }
    
    // Check if we need to fix constraints
    $needsFixing = false;
    foreach ($constraints as $constraint) {
        if (strpos($constraint['REFERENCED_TABLE_NAME'], 'jaktfelt_') !== 0) {
            $needsFixing = true;
            break;
        }
    }
    
    if ($needsFixing) {
        echo "<p>⚠️ Some foreign key constraints need fixing. This requires recreating the database.</p>";
        echo "<p>💡 Run <a href='setup_database.php'>setup_database.php</a> to recreate with correct constraints.</p>";
    } else {
        echo "<p>✅ All foreign key constraints are correct</p>";
        
        // Check if we have data in the tables
        echo "<h3>📊 Data Check:</h3>";
        $dataTables = [
            'jaktfelt_competitions' => 'Competitions',
            'jaktfelt_users' => 'Users',
            'jaktfelt_categories' => 'Categories'
        ];
        
        $hasData = false;
        foreach ($dataTables as $table => $name) {
            try {
                $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
                echo "<p>$name: $count records</p>";
                if ($count > 0) {
                    $hasData = true;
                }
            } catch (PDOException $e) {
                echo "<p>$name: Error - " . $e->getMessage() . "</p>";
            }
        }
        
        if ($hasData) {
            echo "<p>✅ Database is ready for importing results</p>";
            echo "<p>💡 You can now run <a href='import_results_manually.php'>import_results_manually.php</a></p>";
        } else {
            echo "<p>⚠️ No data in tables. Run <a href='setup_sample_data_fixed.php'>setup_sample_data_fixed.php</a> first.</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>
