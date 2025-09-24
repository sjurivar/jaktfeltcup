<?php
/**
 * Import Test Results
 * Import test results data
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Import Test Results - Database Admin';
$current_page = 'admin';
$body_class = 'bg-light';

// Include header
include_header();

// Database configuration
$host = $db_config['host'];
$user = $db_config['user'];
$password = $db_config['password'];
$dbname = $db_config['name'];

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='container mt-4'>";
    echo "<h1><i class='fas fa-trophy me-2'></i>Import Test Results</h1>";
    echo "<p class='lead'>Import test results for competitions</p>";
    
    // Check if results table exists and has is_test_data column
    $result = $pdo->query("SHOW COLUMNS FROM jaktfelt_results LIKE 'is_test_data'");
    if ($result->rowCount() == 0) {
        echo "<div class='alert alert-danger'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Missing Column</h4>";
        echo "<p>The <code>is_test_data</code> column is missing from the results table.</p>";
        echo "<p>Please run <a href='migrate_add_test_data_column.php'>Add Test Data Column</a> first.</p>";
        echo "</div>";
        echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
        echo "</div>";
        include_footer();
        exit;
    }
    
    // Check if we already have test results
    $existingResults = $pdo->query("SELECT COUNT(*) FROM jaktfelt_results WHERE is_test_data = TRUE")->fetchColumn();
    if ($existingResults > 0) {
        echo "<div class='alert alert-warning'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Existing Test Results</h4>";
        echo "<p>Already have $existingResults test results. New results will be added.</p>";
        echo "</div>";
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
    
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-cogs me-2'></i>Import Operations</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<p>Importing " . count($resultsData) . " test results...</p>";
    
    $importedCount = 0;
    $errorCount = 0;
    
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
            echo "<p class='text-success'><i class='fas fa-check me-2'></i>Result " . ($index + 1) . " imported (Competition {$result['competition_id']}, User {$result['user_id']}, Score {$result['score']})</p>";
        } catch (PDOException $e) {
            $errorCount++;
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                echo "<p class='text-warning'><i class='fas fa-exclamation-triangle me-2'></i>Result " . ($index + 1) . " skipped - duplicate entry</p>";
            } else {
                echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Result " . ($index + 1) . " failed: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "</div>";
    echo "</div>";
    
    // Summary
    echo "<div class='alert alert-info mt-4'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Import Summary</h5>";
    echo "<ul>";
    echo "<li><strong>Successfully imported:</strong> $importedCount results</li>";
    echo "<li><strong>Errors:</strong> $errorCount results</li>";
    echo "</ul>";
    echo "</div>";
    
    // Show final count
    $finalCount = $pdo->query("SELECT COUNT(*) FROM jaktfelt_results WHERE is_test_data = TRUE")->fetchColumn();
    echo "<div class='alert alert-success'>";
    echo "<h5><i class='fas fa-check me-2'></i>Total Test Results</h5>";
    echo "<p>Total test results in database: <strong>$finalCount</strong></p>";
    echo "</div>";
    
    if ($importedCount > 0) {
        echo "<div class='alert alert-success'>";
        echo "<h5><i class='fas fa-check me-2'></i>Results Imported Successfully</h5>";
        echo "<p>Test results have been imported into the database.</p>";
        echo "</div>";
    }
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo " <a href='check_data.php' class='btn btn-info'><i class='fas fa-chart-bar me-2'></i>Check Data</a>";
    echo " <a href='/jaktfeltcup/' class='btn btn-success'><i class='fas fa-home me-2'></i>Go to Jaktfeltcup</a>";
    echo "</div>";
    
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='container mt-4'>";
    echo "<div class='alert alert-danger'>";
    echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Database Error</h4>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo "</div>";
}

include_footer();
?>
