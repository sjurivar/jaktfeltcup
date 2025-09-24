<?php
/**
 * Check Database Tables
 * See what tables actually exist in the database
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Check Tables - Database Admin';
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
    echo "<h1><i class='fas fa-list me-2'></i>Check Database Tables</h1>";
    echo "<p class='lead'>Overview of all tables in the database</p>";
    
    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<div class='alert alert-info'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Database: $dbname</h5>";
    echo "<p>Found " . count($tables) . " tables</p>";
    echo "</div>";
    
    // Check for old vs new table names
    $oldTables = ['competitions', 'users', 'seasons', 'categories', 'results', 'registrations', 'point_systems', 'point_rules', 'season_point_systems', 'email_verifications', 'notifications', 'offline_sync', 'audit_log'];
    $newTables = ['jaktfelt_competitions', 'jaktfelt_users', 'jaktfelt_seasons', 'jaktfelt_categories', 'jaktfelt_results', 'jaktfelt_registrations', 'jaktfelt_point_systems', 'jaktfelt_point_rules', 'jaktfelt_season_point_systems', 'jaktfelt_email_verifications', 'jaktfelt_notifications', 'jaktfelt_offline_sync', 'jaktfelt_audit_log'];
    
    echo "<div class='row'>";
    echo "<div class='col-md-6'>";
    echo "<div class='card'>";
    echo "<div class='card-header bg-warning text-dark'>";
    echo "<h5><i class='fas fa-exclamation-triangle me-2'></i>Old Tables (without prefix)</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    $oldExists = [];
    foreach ($oldTables as $oldTable) {
        if (in_array($oldTable, $tables)) {
            $oldExists[] = $oldTable;
            echo "<p class='text-danger'><i class='fas fa-times me-2'></i>$oldTable</p>";
        }
    }
    
    if (empty($oldExists)) {
        echo "<p class='text-success'><i class='fas fa-check me-2'></i>No old tables found</p>";
    }
    
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    echo "<div class='col-md-6'>";
    echo "<div class='card'>";
    echo "<div class='card-header bg-success text-white'>";
    echo "<h5><i class='fas fa-check me-2'></i>New Tables (with prefix)</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    $newExists = [];
    foreach ($newTables as $newTable) {
        if (in_array($newTable, $tables)) {
            $newExists[] = $newTable;
            echo "<p class='text-success'><i class='fas fa-check me-2'></i>$newTable</p>";
        }
    }
    
    if (empty($newExists)) {
        echo "<p class='text-warning'><i class='fas fa-exclamation-triangle me-2'></i>No new tables found</p>";
    }
    
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    // Show all tables
    echo "<div class='card mt-4'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-table me-2'></i>All Tables</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    if (count($tables) > 0) {
        echo "<div class='row'>";
        foreach ($tables as $table) {
            $isOld = in_array($table, $oldTables);
            $isNew = in_array($table, $newTables);
            $badgeClass = $isOld ? 'bg-danger' : ($isNew ? 'bg-success' : 'bg-secondary');
            $badgeText = $isOld ? 'Old' : ($isNew ? 'New' : 'Other');
            
            echo "<div class='col-md-3 mb-2'>";
            echo "<span class='badge $badgeClass me-2'>$badgeText</span>$table";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p class='text-muted'>No tables found</p>";
    }
    
    echo "</div>";
    echo "</div>";
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    if (!empty($oldExists)) {
        echo " <a href='drop_old_tables.php' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to drop all old tables?\")'><i class='fas fa-trash me-2'></i>Drop Old Tables</a>";
    }
    if (empty($newExists)) {
        echo " <a href='setup_database.php' class='btn btn-success'><i class='fas fa-plus me-2'></i>Create New Tables</a>";
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
