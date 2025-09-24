<?php
/**
 * Drop ALL Tables
 * Remove ALL tables in the database
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Drop ALL Tables - Database Admin';
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
    echo "<h1><i class='fas fa-exclamation-triangle me-2'></i>Drop ALL Tables</h1>";
    echo "<p class='lead'>Remove ALL tables in the database</p>";
    
    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<div class='alert alert-success'>";
        echo "<h4><i class='fas fa-check me-2'></i>No Tables Found</h4>";
        echo "<p>No tables found in the database.</p>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>ALL Tables Will Be Dropped</h4>";
        echo "<p>The following tables will be permanently deleted:</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li><code>$table</code></li>";
        }
        echo "</ul>";
        echo "<p class='fw-bold'>This action cannot be undone!</p>";
        echo "</div>";
        
        $droppedCount = 0;
        $errorCount = 0;
        
        echo "<div class='card'>";
        echo "<div class='card-header bg-danger text-white'>";
        echo "<h5><i class='fas fa-cogs me-2'></i>Drop Operations</h5>";
        echo "</div>";
        echo "<div class='card-body'>";
        
        // Disable foreign key checks temporarily
        echo "<p class='text-info'><i class='fas fa-info-circle me-2'></i>Disabling foreign key checks...</p>";
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        foreach ($tables as $table) {
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
        
        if ($droppedCount > 0) {
            echo "<div class='alert alert-success'>";
            echo "<h5><i class='fas fa-check me-2'></i>All Tables Dropped</h5>";
            echo "<p>All tables have been removed from the database.</p>";
            echo "</div>";
        }
    }
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo " <a href='check_tables.php' class='btn btn-info'><i class='fas fa-list me-2'></i>Check Tables</a>";
    echo " <a href='setup_database.php' class='btn btn-success'><i class='fas fa-plus me-2'></i>Create New Tables</a>";
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
