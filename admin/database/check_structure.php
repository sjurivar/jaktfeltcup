<?php
/**
 * Check Database Structure
 * Check if is_test_data column exists in all tables
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Check Structure - Database Admin';
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
    echo "<h1><i class='fas fa-cogs me-2'></i>Check Database Structure</h1>";
    echo "<p class='lead'>Check if is_test_data column exists in all tables</p>";
    
    $tables = [
        'jaktfelt_users', 'jaktfelt_seasons', 'jaktfelt_competitions', 'jaktfelt_categories',
        'jaktfelt_competition_categories', 'jaktfelt_registrations', 'jaktfelt_results',
        'jaktfelt_point_systems', 'jaktfelt_point_rules', 'jaktfelt_season_point_systems',
        'jaktfelt_email_verifications', 'jaktfelt_notifications', 'jaktfelt_offline_sync',
        'jaktfelt_audit_log'
    ];
    
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-table me-2'></i>Table Structure Check</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>Table</th><th>is_test_data Column Exists</th><th>Action</th></tr></thead>";
    echo "<tbody>";
    
    $allColumnsExist = true;
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = 'is_test_data'");
            $stmt->execute([$dbname, $table]);
            $columnExists = $stmt->fetch() !== false;
            
            $status = $columnExists ? 'success' : 'danger';
            $statusText = $columnExists ? 'Yes' : 'No';
            $action = $columnExists ? 'OK' : 'Run migration';
            
            echo "<tr>";
            echo "<td><code>$table</code></td>";
            echo "<td><span class='badge bg-$status'>$statusText</span></td>";
            echo "<td>$action</td>";
            echo "</tr>";
            
            if (!$columnExists) {
                $allColumnsExist = false;
            }
        } catch (PDOException $e) {
            echo "<tr><td><code>$table</code></td><td colspan='2' class='text-danger'>Error: " . $e->getMessage() . "</td></tr>";
            $allColumnsExist = false;
        }
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    if ($allColumnsExist) {
        echo "<div class='alert alert-success mt-4'>";
        echo "<h5><i class='fas fa-check me-2'></i>All Columns Exist</h5>";
        echo "<p>All 'is_test_data' columns exist. You can now import sample data.</p>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-warning mt-4'>";
        echo "<h5><i class='fas fa-exclamation-triangle me-2'></i>Missing Columns</h5>";
        echo "<p>Some 'is_test_data' columns are missing. Please run the migration to add them.</p>";
        echo "</div>";
    }
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    if (!$allColumnsExist) {
        echo " <a href='migrate_add_test_data_column.php' class='btn btn-primary'><i class='fas fa-columns me-2'></i>Add Test Data Column</a>";
    } else {
        echo " <a href='import_sample_data.php' class='btn btn-success'><i class='fas fa-upload me-2'></i>Import Sample Data</a>";
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
