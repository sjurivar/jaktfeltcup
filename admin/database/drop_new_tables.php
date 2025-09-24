<?php
/**
 * Drop New Tables
 * Remove tables with jaktfelt_ prefix
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Drop New Tables - Database Admin';
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
    echo "<h1><i class='fas fa-trash me-2'></i>Drop New Tables</h1>";
    echo "<p class='lead'>Remove tables with jaktfelt_ prefix</p>";
    
    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    // Define new tables to drop
    $newTables = ['jaktfelt_competitions', 'jaktfelt_users', 'jaktfelt_seasons', 'jaktfelt_categories', 'jaktfelt_results', 'jaktfelt_registrations', 'jaktfelt_point_systems', 'jaktfelt_point_rules', 'jaktfelt_season_point_systems', 'jaktfelt_email_verifications', 'jaktfelt_notifications', 'jaktfelt_offline_sync', 'jaktfelt_audit_log', 'jaktfelt_competition_categories'];
    
    $tablesToDrop = [];
    foreach ($newTables as $newTable) {
        if (in_array($newTable, $tables)) {
            $tablesToDrop[] = $newTable;
        }
    }
    
    if (empty($tablesToDrop)) {
        echo "<div class='alert alert-success'>";
        echo "<h4><i class='fas fa-check me-2'></i>No New Tables Found</h4>";
        echo "<p>No tables with jaktfelt_ prefix found to drop.</p>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-warning'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Tables to Drop</h4>";
        echo "<p>The following tables will be permanently deleted:</p>";
        echo "<ul>";
        foreach ($tablesToDrop as $table) {
            echo "<li><code>$table</code></li>";
        }
        echo "</ul>";
        echo "</div>";
        
        $droppedCount = 0;
        $errorCount = 0;
        
        echo "<div class='card'>";
        echo "<div class='card-header'>";
        echo "<h5><i class='fas fa-cogs me-2'></i>Drop Operations</h5>";
        echo "</div>";
        echo "<div class='card-body'>";
        
        // Disable foreign key checks temporarily
        echo "<p class='text-info'><i class='fas fa-info-circle me-2'></i>Disabling foreign key checks...</p>";
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        foreach ($tablesToDrop as $table) {
            try {
                $pdo->exec("DROP TABLE `$table`");
                $droppedCount++;
                echo "<p class='text-success'><i class='fas fa-check me-2'></i>Dropped table: <code>$table</code></p>";
            } catch (PDOException $e) {
                $errorCount++;
                echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error dropping table <code>$table</code>: " . $e->getMessage() . "</p>";
            }
        }
        
        // Re-enable foreign key checks
        echo "<p class='text-info'><i class='fas fa-info-circle me-2'></i>Re-enabling foreign key checks...</p>";
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        
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
