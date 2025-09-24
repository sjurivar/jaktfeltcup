<?php
/**
 * Check Imported Data
 * Verify what data was actually imported
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Check Data - Database Admin';
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
    echo "<h1><i class='fas fa-chart-bar me-2'></i>Check Imported Data</h1>";
    echo "<p class='lead'>Overview of data in all tables</p>";
    
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
    
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-table me-2'></i>Data Summary</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>Table</th><th>Test Records</th><th>Total Records</th><th>Status</th><th>Sample Data</th></tr></thead>";
    echo "<tbody>";
    
    $totalTestRecords = 0;
    $totalRecords = 0;
    
    foreach ($tables as $table => $name) {
        try {
            // Check if table exists
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() == 0) {
                echo "<tr><td>$name</td><td colspan='4' class='text-danger'>Table does not exist</td></tr>";
                continue;
            }
            
            // Check if is_test_data column exists
            $result = $pdo->query("SHOW COLUMNS FROM $table LIKE 'is_test_data'");
            if ($result->rowCount() == 0) {
                echo "<tr><td>$name</td><td colspan='4' class='text-warning'>Missing is_test_data column</td></tr>";
                continue;
            }
            
            // Count test data
            $testCount = $pdo->query("SELECT COUNT(*) FROM $table WHERE is_test_data = TRUE")->fetchColumn();
            $totalCount = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            
            $totalTestRecords += $testCount;
            $totalRecords += $totalCount;
            
            // Get sample data
            $sampleData = '';
            if ($testCount > 0) {
                $sample = $pdo->query("SELECT * FROM $table WHERE is_test_data = TRUE LIMIT 1")->fetch();
                if ($sample) {
                    $sampleData = json_encode($sample, JSON_PRETTY_PRINT);
                }
            }
            
            $status = $testCount > 0 ? 'success' : 'warning';
            $statusText = $testCount > 0 ? 'Has test data' : 'No test data';
            
            echo "<tr>";
            echo "<td><strong>$name</strong></td>";
            echo "<td><span class='badge bg-$status'>$testCount</span></td>";
            echo "<td>$totalCount</td>";
            echo "<td><span class='badge bg-$status'>$statusText</span></td>";
            echo "<td><pre class='small'>" . htmlspecialchars(substr($sampleData, 0, 100)) . "</pre></td>";
            echo "</tr>";
            
        } catch (PDOException $e) {
            echo "<tr><td>$name</td><td colspan='4' class='text-danger'>Error: " . $e->getMessage() . "</td></tr>";
        }
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    // Summary
    echo "<div class='row mt-4'>";
    echo "<div class='col-md-6'>";
    echo "<div class='card'>";
    echo "<div class='card-header bg-info text-white'>";
    echo "<h5><i class='fas fa-chart-pie me-2'></i>Summary</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<p><strong>Total Test Records:</strong> $totalTestRecords</p>";
    echo "<p><strong>Total Records:</strong> $totalRecords</p>";
    echo "<p><strong>Test Data Percentage:</strong> " . ($totalRecords > 0 ? round(($totalTestRecords / $totalRecords) * 100, 1) : 0) . "%</p>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    echo "<div class='col-md-6'>";
    echo "<div class='card'>";
    echo "<div class='card-header bg-success text-white'>";
    echo "<h5><i class='fas fa-users me-2'></i>Test Users</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<ul class='list-unstyled'>";
    echo "<li><strong>Admin:</strong> testadmin / password</li>";
    echo "<li><strong>Organizer:</strong> testorganizer / password</li>";
    echo "<li><strong>Participants:</strong> testdeltaker1-10 / password</li>";
    echo "</ul>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    if ($totalTestRecords == 0) {
        echo " <a href='import_sample_data.php' class='btn btn-primary'><i class='fas fa-upload me-2'></i>Import Sample Data</a>";
    } else {
        echo " <a href='clear_test_data.php' class='btn btn-warning' onclick='return confirm(\"Are you sure you want to clear all test data?\")'><i class='fas fa-broom me-2'></i>Clear Test Data</a>";
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
