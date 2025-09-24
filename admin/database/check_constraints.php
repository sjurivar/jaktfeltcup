<?php
/**
 * Check Foreign Key Constraints
 * Check foreign key constraints and their references
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Check Constraints - Database Admin';
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
    echo "<h1><i class='fas fa-link me-2'></i>Check Foreign Key Constraints</h1>";
    echo "<p class='lead'>Check foreign key constraints and their references</p>";
    
    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    // Check for old vs new table names
    $oldTables = ['competitions', 'users', 'seasons', 'categories', 'results', 'registrations', 'point_systems', 'point_rules', 'season_point_systems', 'email_verifications', 'notifications', 'offline_sync', 'audit_log'];
    $newTables = ['jaktfelt_competitions', 'jaktfelt_users', 'jaktfelt_seasons', 'jaktfelt_categories', 'jaktfelt_results', 'jaktfelt_registrations', 'jaktfelt_point_systems', 'jaktfelt_point_rules', 'jaktfelt_season_point_systems', 'jaktfelt_email_verifications', 'jaktfelt_notifications', 'jaktfelt_offline_sync', 'jaktfelt_audit_log'];
    
    echo "<div class='row mb-4'>";
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
    
    // Check foreign key constraints
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-link me-2'></i>Foreign Key Constraints</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    try {
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
        
        if (count($constraints) > 0) {
            echo "<div class='table-responsive'>";
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>Table</th><th>Column</th><th>Constraint</th><th>References</th><th>Status</th></tr></thead>";
            echo "<tbody>";
            
            $needsFixing = false;
            foreach ($constraints as $constraint) {
                $status = '';
                $statusClass = '';
                if (strpos($constraint['REFERENCED_TABLE_NAME'], 'jaktfelt_') === 0) {
                    $status = 'Correct';
                    $statusClass = 'success';
                } else {
                    $status = 'Needs fixing';
                    $statusClass = 'danger';
                    $needsFixing = true;
                }
                
                echo "<tr>";
                echo "<td><code>" . $constraint['TABLE_NAME'] . "</code></td>";
                echo "<td>" . $constraint['COLUMN_NAME'] . "</td>";
                echo "<td>" . $constraint['CONSTRAINT_NAME'] . "</td>";
                echo "<td><code>" . $constraint['REFERENCED_TABLE_NAME'] . "." . $constraint['REFERENCED_COLUMN_NAME'] . "</code></td>";
                echo "<td><span class='badge bg-$statusClass'>$status</span></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            
            if ($needsFixing) {
                echo "<div class='alert alert-warning mt-3'>";
                echo "<h5><i class='fas fa-exclamation-triangle me-2'></i>Constraints Need Fixing</h5>";
                echo "<p>Some foreign key constraints reference old table names. This requires recreating the database.</p>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-success mt-3'>";
                echo "<h5><i class='fas fa-check me-2'></i>All Constraints Correct</h5>";
                echo "<p>All foreign key constraints reference the correct table names.</p>";
                echo "</div>";
            }
        } else {
            echo "<p class='text-muted'>No foreign key constraints found</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Error checking constraints: " . $e->getMessage() . "</p>";
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
