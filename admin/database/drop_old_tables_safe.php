<?php
/**
 * Drop Old Tables (Safe Order)
 * Remove tables without jaktfelt_ prefix in correct order
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Drop Old Tables (Safe) - Database Admin';
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
    echo "<h1><i class='fas fa-trash me-2'></i>Drop Old Tables (Safe Order)</h1>";
    echo "<p class='lead'>Remove tables without jaktfelt_ prefix in correct order</p>";
    
    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    // Define old tables to drop in correct order (children first, parents last)
    $oldTablesOrdered = [
        // Children tables first (those with foreign keys)
        'results',
        'registrations', 
        'competition_categories',
        'season_point_systems',
        'point_rules',
        'email_verifications',
        'notifications',
        'offline_sync',
        'audit_log',
        
        // Parent tables last (those referenced by foreign keys)
        'competitions',
        'users',
        'seasons',
        'categories',
        'point_systems'
    ];
    
    $tablesToDrop = [];
    foreach ($oldTablesOrdered as $oldTable) {
        if (in_array($oldTable, $tables)) {
            $tablesToDrop[] = $oldTable;
        }
    }
    
    if (empty($tablesToDrop)) {
        echo "<div class='alert alert-success'>";
        echo "<h4><i class='fas fa-check me-2'></i>No Old Tables Found</h4>";
        echo "<p>All tables already have the correct jaktfelt_ prefix or don't exist.</p>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-warning'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Tables to Drop (in order)</h4>";
        echo "<p>The following tables will be permanently deleted in this order:</p>";
        echo "<ol>";
        foreach ($tablesToDrop as $table) {
            echo "<li><code>$table</code></li>";
        }
        echo "</ol>";
        echo "</div>";
        
        $droppedCount = 0;
        $errorCount = 0;
        
        echo "<div class='card'>";
        echo "<div class='card-header'>";
        echo "<h5><i class='fas fa-cogs me-2'></i>Drop Operations (Safe Order)</h5>";
        echo "</div>";
        echo "<div class='card-body'>";
        
        foreach ($tablesToDrop as $index => $table) {
            try {
                $pdo->exec("DROP TABLE `$table`");
                $droppedCount++;
                echo "<p class='text-success'><i class='fas fa-check me-2'></i>Step " . ($index + 1) . ": Dropped table <code>$table</code></p>";
            } catch (PDOException $e) {
                $errorCount++;
                echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Step " . ($index + 1) . ": Error dropping table <code>$table</code>: " . $e->getMessage() . "</p>";
            }
        }
        
        echo "</div>";
        echo "</div>";
        
        echo "<div class='alert alert-info mt-4'>";
        echo "<h5><i class='fas fa-info-circle me-2'></i>Operation Summary</h5>";
        echo "<ul>";
        echo "<li><strong>Tables dropped:</strong> $droppedCount</li>";
        echo "<li><strong>Errors:</strong> $errorCount</li>";
        echo "</ul>";
        echo "</div>";
    }
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo " <a href='check_tables.php' class='btn btn-info'><i class='fas fa-list me-2'></i>Check Tables</a>";
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
