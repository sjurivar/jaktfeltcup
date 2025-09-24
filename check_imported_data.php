<?php
/**
 * Check Imported Data
 * Verify what data was actually imported
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
    
    echo "<p>✅ Connected to database</p>";
    
    // Check all tables
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
    
    echo "<h3>📋 Database Content Summary:</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Table</th><th>Test Records</th><th>Total Records</th><th>Sample Data</th></tr>";
    
    foreach ($tables as $table => $name) {
        try {
            // Check if table exists
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() == 0) {
                echo "<tr><td>$name</td><td colspan='3'>Table does not exist</td></tr>";
                continue;
            }
            
            // Check if is_test_data column exists
            $result = $pdo->query("SHOW COLUMNS FROM $table LIKE 'is_test_data'");
            if ($result->rowCount() == 0) {
                echo "<tr><td>$name</td><td colspan='3'>Missing is_test_data column</td></tr>";
                continue;
            }
            
            // Count test data
            $testCount = $pdo->query("SELECT COUNT(*) FROM $table WHERE is_test_data = TRUE")->fetchColumn();
            $totalCount = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            
            // Get sample data
            $sampleData = '';
            if ($testCount > 0) {
                $sample = $pdo->query("SELECT * FROM $table WHERE is_test_data = TRUE LIMIT 1")->fetch();
                if ($sample) {
                    $sampleData = json_encode($sample, JSON_PRETTY_PRINT);
                }
            }
            
            echo "<tr>";
            echo "<td><strong>$name</strong></td>";
            echo "<td>$testCount</td>";
            echo "<td>$totalCount</td>";
            echo "<td><pre>" . htmlspecialchars(substr($sampleData, 0, 200)) . "</pre></td>";
            echo "</tr>";
            
        } catch (PDOException $e) {
            echo "<tr><td>$name</td><td colspan='3'>Error: " . $e->getMessage() . "</td></tr>";
        }
    }
    echo "</table>";
    
    // Show specific results data
    echo "<h3>🎯 Results Data Details:</h3>";
    try {
        $results = $pdo->query("SELECT * FROM jaktfelt_results WHERE is_test_data = TRUE ORDER BY competition_id, category_id, position")->fetchAll();
        if (count($results) > 0) {
            echo "<p>✅ Found " . count($results) . " test results</p>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>Competition ID</th><th>User ID</th><th>Category ID</th><th>Score</th><th>Position</th><th>Points</th></tr>";
            foreach ($results as $result) {
                echo "<tr>";
                echo "<td>" . $result['competition_id'] . "</td>";
                echo "<td>" . $result['user_id'] . "</td>";
                echo "<td>" . $result['category_id'] . "</td>";
                echo "<td>" . $result['score'] . "</td>";
                echo "<td>" . $result['position'] . "</td>";
                echo "<td>" . $result['points_awarded'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>❌ No test results found</p>";
        }
    } catch (PDOException $e) {
        echo "<p>❌ Error checking results: " . $e->getMessage() . "</p>";
    }
    
    // Show competitions data
    echo "<h3>🏆 Competitions Data Details:</h3>";
    try {
        $competitions = $pdo->query("SELECT * FROM jaktfelt_competitions WHERE is_test_data = TRUE ORDER BY date")->fetchAll();
        if (count($competitions) > 0) {
            echo "<p>✅ Found " . count($competitions) . " test competitions</p>";
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>ID</th><th>Name</th><th>Date</th><th>Location</th><th>Status</th></tr>";
            foreach ($competitions as $comp) {
                echo "<tr>";
                echo "<td>" . $comp['id'] . "</td>";
                echo "<td>" . $comp['name'] . "</td>";
                echo "<td>" . $comp['date'] . "</td>";
                echo "<td>" . $comp['location'] . "</td>";
                echo "<td>" . $comp['status'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>❌ No test competitions found</p>";
        }
    } catch (PDOException $e) {
        echo "<p>❌ Error checking competitions: " . $e->getMessage() . "</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>
