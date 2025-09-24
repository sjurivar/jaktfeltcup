<?php
/**
 * Clear Test Data
 * Remove all records marked as test data
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Clear Test Data - Database Admin';
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
    echo "<h1><i class='fas fa-broom me-2'></i>Clear Test Data</h1>";
    echo "<p class='lead'>Remove all records marked as test data</p>";
    
    // Tables to clear
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
    
    $totalCleared = 0;
    $totalErrors = 0;
    
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-cogs me-2'></i>Clear Operations</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    foreach ($tables as $table => $name) {
        try {
            // Check if table exists
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() == 0) {
                echo "<p class='text-muted'><i class='fas fa-info-circle me-2'></i>Table <code>$table</code> does not exist - skipping</p>";
                continue;
            }
            
            // Check if is_test_data column exists
            $result = $pdo->query("SHOW COLUMNS FROM $table LIKE 'is_test_data'");
            if ($result->rowCount() == 0) {
                echo "<p class='text-muted'><i class='fas fa-info-circle me-2'></i>Table <code>$table</code> missing is_test_data column - skipping</p>";
                continue;
            }
            
            // Count test data before clearing
            $countBefore = $pdo->query("SELECT COUNT(*) FROM $table WHERE is_test_data = TRUE")->fetchColumn();
            
            if ($countBefore > 0) {
                // Clear test data
                $stmt = $pdo->prepare("DELETE FROM $table WHERE is_test_data = TRUE");
                $stmt->execute();
                $cleared = $stmt->rowCount();
                
                $totalCleared += $cleared;
                echo "<p class='text-success'><i class='fas fa-check me-2'></i>Cleared $cleared test records from <code>$table</code></p>";
            } else {
                echo "<p class='text-muted'><i class='fas fa-info-circle me-2'></i>No test data in <code>$table</code></p>";
            }
            
        } catch (PDOException $e) {
            $totalErrors++;
            echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error clearing <code>$table</code>: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "</div>";
    echo "</div>";
    
    // Summary
    echo "<div class='alert alert-info mt-4'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Operation Summary</h5>";
    echo "<ul>";
    echo "<li><strong>Test records cleared:</strong> $totalCleared</li>";
    echo "<li><strong>Errors:</strong> $totalErrors</li>";
    echo "</ul>";
    echo "</div>";
    
    if ($totalCleared > 0) {
        echo "<div class='alert alert-success'>";
        echo "<h5><i class='fas fa-check me-2'></i>Test Data Cleared Successfully</h5>";
        echo "<p>All test data has been removed from the database.</p>";
        echo "</div>";
    }
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo " <a href='check_data.php' class='btn btn-info'><i class='fas fa-chart-bar me-2'></i>Check Data</a>";
    echo " <a href='import_sample_data.php' class='btn btn-primary'><i class='fas fa-upload me-2'></i>Import Sample Data</a>";
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
