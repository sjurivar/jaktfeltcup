<?php
/**
 * Add Test Data Column
 * Add is_test_data column to all tables
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Add Test Data Column - Database Admin';
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
    echo "<h1><i class='fas fa-columns me-2'></i>Add Test Data Column</h1>";
    echo "<p class='lead'>Add is_test_data column to all tables</p>";
    
    // List of tables to migrate
    $tables = [
        'jaktfelt_users',
        'jaktfelt_seasons', 
        'jaktfelt_competitions',
        'jaktfelt_categories',
        'jaktfelt_competition_categories',
        'jaktfelt_registrations',
        'jaktfelt_results',
        'jaktfelt_point_systems',
        'jaktfelt_point_rules',
        'jaktfelt_season_point_systems',
        'jaktfelt_email_verifications',
        'jaktfelt_notifications',
        'jaktfelt_offline_sync',
        'jaktfelt_audit_log'
    ];
    
    $executedCount = 0;
    $errorCount = 0;
    
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-cogs me-2'></i>Migration Operations</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    foreach ($tables as $table) {
        echo "<h6>Processing table: $table</h6>";
        
        try {
            // Check if table exists
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() == 0) {
                echo "<p class='text-muted'><i class='fas fa-info-circle me-2'></i>Table $table does not exist, skipping...</p>";
                continue;
            }
            
            // Check if is_test_data column exists
            $result = $pdo->query("SHOW COLUMNS FROM $table LIKE 'is_test_data'");
            if ($result->rowCount() > 0) {
                echo "<p class='text-success'><i class='fas fa-check me-2'></i>Column is_test_data already exists in $table</p>";
            } else {
                // Add the column
                $sql = "ALTER TABLE $table ADD COLUMN is_test_data BOOLEAN DEFAULT FALSE";
                $pdo->exec($sql);
                echo "<p class='text-success'><i class='fas fa-check me-2'></i>Added is_test_data column to $table</p>";
                $executedCount++;
            }
            
            // Check if index exists
            $indexName = 'idx_' . str_replace('jaktfelt_', '', $table) . '_test_data';
            $result = $pdo->query("SHOW INDEX FROM $table WHERE Key_name = '$indexName'");
            if ($result->rowCount() > 0) {
                echo "<p class='text-success'><i class='fas fa-check me-2'></i>Index $indexName already exists</p>";
            } else {
                // Add the index
                $sql = "CREATE INDEX $indexName ON $table(is_test_data)";
                $pdo->exec($sql);
                echo "<p class='text-success'><i class='fas fa-check me-2'></i>Added index $indexName to $table</p>";
                $executedCount++;
            }
            
        } catch (PDOException $e) {
            $errorCount++;
            echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error processing $table: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "</div>";
    echo "</div>";
    
    // Summary
    echo "<div class='alert alert-info mt-4'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Migration Summary</h5>";
    echo "<ul>";
    echo "<li><strong>Operations executed:</strong> $executedCount</li>";
    echo "<li><strong>Errors:</strong> $errorCount</li>";
    echo "</ul>";
    echo "</div>";
    
    if ($executedCount > 0) {
        echo "<div class='alert alert-success'>";
        echo "<h5><i class='fas fa-check me-2'></i>Migration Complete</h5>";
        echo "<p>Test data columns and indexes have been added successfully.</p>";
        echo "</div>";
    }
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo " <a href='check_structure.php' class='btn btn-info'><i class='fas fa-cogs me-2'></i>Check Structure</a>";
    if ($executedCount > 0) {
        echo " <a href='import_sample_data.php' class='btn btn-primary'><i class='fas fa-upload me-2'></i>Import Sample Data</a>";
    }
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
