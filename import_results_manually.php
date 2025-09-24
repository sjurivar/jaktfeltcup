<?php
/**
 * Import Results Manually
 * Import just the results data if it's missing
 */

echo "<h2>Jaktfeltcup - Import Results Manually</h2>";

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
    
    // Check if results table exists and has is_test_data column
    $result = $pdo->query("SHOW COLUMNS FROM jaktfelt_results LIKE 'is_test_data'");
    if ($result->rowCount() == 0) {
        echo "<p>❌ is_test_data column missing from results table</p>";
        echo "<p>💡 Run <a href='fix_database_structure.php'>fix_database_structure.php</a> first</p>";
        exit;
    }
    
    // Check if we already have test results
    $existingResults = $pdo->query("SELECT COUNT(*) FROM jaktfelt_results WHERE is_test_data = TRUE")->fetchColumn();
    if ($existingResults > 0) {
        echo "<p>⚠️ Already have $existingResults test results. Do you want to add more?</p>";
    }
    
    // Sample results data
    $resultsData = [
        // Vårstevnet 2024 results
        ['competition_id' => 1, 'user_id' => 3, 'category_id' => 1, 'score' => 95, 'position' => 1, 'points_awarded' => 100, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-04-15 16:30:00', 'is_test_data' => true],
        ['competition_id' => 1, 'user_id' => 5, 'category_id' => 1, 'score' => 92, 'position' => 2, 'points_awarded' => 90, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-04-15 16:35:00', 'is_test_data' => true],
        ['competition_id' => 1, 'user_id' => 7, 'category_id' => 1, 'score' => 89, 'position' => 3, 'points_awarded' => 80, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-04-15 16:40:00', 'is_test_data' => true],
        ['competition_id' => 1, 'user_id' => 10, 'category_id' => 1, 'score' => 87, 'position' => 4, 'points_awarded' => 70, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-04-15 16:45:00', 'is_test_data' => true],
        ['competition_id' => 1, 'user_id' => 4, 'category_id' => 4, 'score' => 93, 'position' => 1, 'points_awarded' => 100, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-04-15 16:50:00', 'is_test_data' => true],
        ['competition_id' => 1, 'user_id' => 11, 'category_id' => 4, 'score' => 91, 'position' => 2, 'points_awarded' => 90, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-04-15 16:55:00', 'is_test_data' => true],
        ['competition_id' => 1, 'user_id' => 6, 'category_id' => 2, 'score' => 88, 'position' => 1, 'points_awarded' => 100, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-04-15 17:00:00', 'is_test_data' => true],
        ['competition_id' => 1, 'user_id' => 9, 'category_id' => 2, 'score' => 85, 'position' => 2, 'points_awarded' => 90, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-04-15 17:05:00', 'is_test_data' => true],
        ['competition_id' => 1, 'user_id' => 12, 'category_id' => 2, 'score' => 82, 'position' => 3, 'points_awarded' => 80, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-04-15 17:10:00', 'is_test_data' => true],
        ['competition_id' => 1, 'user_id' => 8, 'category_id' => 3, 'score' => 90, 'position' => 1, 'points_awarded' => 100, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-04-15 17:15:00', 'is_test_data' => true],
        
        // Sommerstevnet 2024 results
        ['competition_id' => 2, 'user_id' => 3, 'category_id' => 1, 'score' => 97, 'position' => 1, 'points_awarded' => 100, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-06-20 16:30:00', 'is_test_data' => true],
        ['competition_id' => 2, 'user_id' => 5, 'category_id' => 1, 'score' => 94, 'position' => 2, 'points_awarded' => 90, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-06-20 16:35:00', 'is_test_data' => true],
        ['competition_id' => 2, 'user_id' => 7, 'category_id' => 1, 'score' => 91, 'position' => 3, 'points_awarded' => 80, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-06-20 16:40:00', 'is_test_data' => true],
        ['competition_id' => 2, 'user_id' => 10, 'category_id' => 1, 'score' => 88, 'position' => 4, 'points_awarded' => 70, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-06-20 16:45:00', 'is_test_data' => true],
        ['competition_id' => 2, 'user_id' => 4, 'category_id' => 4, 'score' => 95, 'position' => 1, 'points_awarded' => 100, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-06-20 16:50:00', 'is_test_data' => true],
        ['competition_id' => 2, 'user_id' => 11, 'category_id' => 4, 'score' => 93, 'position' => 2, 'points_awarded' => 90, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-06-20 16:55:00', 'is_test_data' => true],
        ['competition_id' => 2, 'user_id' => 6, 'category_id' => 2, 'score' => 90, 'position' => 1, 'points_awarded' => 100, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-06-20 17:00:00', 'is_test_data' => true],
        ['competition_id' => 2, 'user_id' => 9, 'category_id' => 2, 'score' => 87, 'position' => 2, 'points_awarded' => 90, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-06-20 17:05:00', 'is_test_data' => true],
        ['competition_id' => 2, 'user_id' => 12, 'category_id' => 2, 'score' => 84, 'position' => 3, 'points_awarded' => 80, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-06-20 17:10:00', 'is_test_data' => true],
        ['competition_id' => 2, 'user_id' => 8, 'category_id' => 3, 'score' => 92, 'position' => 1, 'points_awarded' => 100, 'is_walk_in' => false, 'notes' => 'Test resultat', 'entered_by' => 2, 'entered_at' => '2024-06-20 17:15:00', 'is_test_data' => true],
    ];
    
    $importedCount = 0;
    $errorCount = 0;
    
    echo "<p>🔄 Importing " . count($resultsData) . " test results...</p>";
    
    foreach ($resultsData as $index => $result) {
        try {
            $sql = "INSERT INTO jaktfelt_results (competition_id, user_id, category_id, score, position, points_awarded, is_walk_in, notes, entered_by, entered_at, is_test_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $result['competition_id'],
                $result['user_id'],
                $result['category_id'],
                $result['score'],
                $result['position'],
                $result['points_awarded'],
                $result['is_walk_in'],
                $result['notes'],
                $result['entered_by'],
                $result['entered_at'],
                $result['is_test_data']
            ]);
            $importedCount++;
            echo "<p>✅ Imported result " . ($index + 1) . " (Competition {$result['competition_id']}, User {$result['user_id']}, Score {$result['score']})</p>";
        } catch (PDOException $e) {
            $errorCount++;
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                echo "<p>⚠️ Duplicate result " . ($index + 1) . " - skipping</p>";
            } else {
                echo "<p>❌ Error importing result " . ($index + 1) . ": " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "<h3>📊 Import Summary:</h3>";
    echo "<ul>";
    echo "<li>✅ Successfully imported: $importedCount results</li>";
    echo "<li>❌ Errors: $errorCount results</li>";
    echo "</ul>";
    
    // Show final count
    $finalCount = $pdo->query("SELECT COUNT(*) FROM jaktfelt_results WHERE is_test_data = TRUE")->fetchColumn();
    echo "<p><strong>Total test results in database: $finalCount</strong></p>";
    
    if ($importedCount > 0) {
        echo "<p><strong>✅ Results import complete!</strong></p>";
        echo "<p><a href='check_imported_data.php'>Check all imported data</a> | <a href='/jaktfeltcup/'>Go to Jaktfeltcup</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>
