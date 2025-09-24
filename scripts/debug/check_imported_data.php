<?php
/**
 * Check Imported Data
 * Verify imported sample data
 */

echo "<h2>Jaktfeltcup - Check Imported Data</h2>";

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
    
    // Check test data in each table
    $tables = [
        'jaktfelt_users' => 'Users',
        'jaktfelt_seasons' => 'Seasons',
        'jaktfelt_competitions' => 'Competitions',
        'jaktfelt_categories' => 'Categories',
        'jaktfelt_registrations' => 'Registrations',
        'jaktfelt_results' => 'Results',
        'jaktfelt_point_systems' => 'Point Systems'
    ];
    
    echo "<h3>Test Data Summary:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Table</th><th>Description</th><th>Test Records</th><th>Total Records</th></tr>";
    
    foreach ($tables as $table => $description) {
        try {
            $testCount = $pdo->query("SELECT COUNT(*) FROM $table WHERE is_test_data = 1")->fetchColumn();
            $totalCount = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            
            echo "<tr>";
            echo "<td>$table</td>";
            echo "<td>$description</td>";
            echo "<td>$testCount</td>";
            echo "<td>$totalCount</td>";
            echo "</tr>";
            
        } catch (PDOException $e) {
            echo "<tr>";
            echo "<td>$table</td>";
            echo "<td>$description</td>";
            echo "<td>Error</td>";
            echo "<td>Error</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    // Check specific data
    echo "<h3>Detailed Checks:</h3>";
    
    // Check users
    try {
        $users = $pdo->query("SELECT COUNT(*) FROM jaktfelt_users WHERE is_test_data = 1")->fetchColumn();
        echo "<p>✅ Test users: $users</p>";
    } catch (PDOException $e) {
        echo "<p>❌ Error checking users: " . $e->getMessage() . "</p>";
    }
    
    // Check competitions
    try {
        $competitions = $pdo->query("SELECT COUNT(*) FROM jaktfelt_competitions WHERE is_test_data = 1")->fetchColumn();
        echo "<p>✅ Test competitions: $competitions</p>";
    } catch (PDOException $e) {
        echo "<p>❌ Error checking competitions: " . $e->getMessage() . "</p>";
    }
    
    // Check results
    try {
        $results = $pdo->query("SELECT COUNT(*) FROM jaktfelt_results WHERE is_test_data = 1")->fetchColumn();
        echo "<p>✅ Test results: $results</p>";
        
        if ($results > 0) {
            $avgScore = $pdo->query("SELECT AVG(score) FROM jaktfelt_results WHERE is_test_data = 1")->fetchColumn();
            echo "<p>📊 Average test score: " . round($avgScore, 2) . "</p>";
        }
    } catch (PDOException $e) {
        echo "<p>❌ Error checking results: " . $e->getMessage() . "</p>";
    }
    
    // Check registrations
    try {
        $registrations = $pdo->query("SELECT COUNT(*) FROM jaktfelt_registrations WHERE is_test_data = 1")->fetchColumn();
        echo "<p>✅ Test registrations: $registrations</p>";
    } catch (PDOException $e) {
        echo "<p>❌ Error checking registrations: " . $e->getMessage() . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure XAMPP MySQL is running and database '$dbname' exists and credentials are correct.</p>";
}
?>
