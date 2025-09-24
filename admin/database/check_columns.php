<?php
/**
 * Check Database Columns
 * Check what columns actually exist in the database tables
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Check Columns - Database Admin';
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
    echo "<h1><i class='fas fa-columns me-2'></i>Check Database Columns</h1>";
    echo "<p class='lead'>Check what columns actually exist in database tables</p>";
    
    // Get all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-table me-2'></i>Table Column Structure</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    foreach ($tables as $table) {
        echo "<h6><code>$table</code></h6>";
        
        try {
            $columns = $pdo->query("SHOW COLUMNS FROM $table")->fetchAll();
            
            echo "<div class='table-responsive mb-4'>";
            echo "<table class='table table-sm table-striped'>";
            echo "<thead><tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr></thead>";
            echo "<tbody>";
            
            foreach ($columns as $column) {
                $key = $column['Key'] ? "<span class='badge bg-info'>" . $column['Key'] . "</span>" : '';
                $null = $column['Null'] === 'YES' ? "<span class='badge bg-warning'>NULL</span>" : "<span class='badge bg-success'>NOT NULL</span>";
                
                echo "<tr>";
                echo "<td><code>" . $column['Field'] . "</code></td>";
                echo "<td>" . $column['Type'] . "</td>";
                echo "<td>$null</td>";
                echo "<td>$key</td>";
                echo "<td>" . ($column['Default'] ?: '<em>NULL</em>') . "</td>";
                echo "<td>" . $column['Extra'] . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            
        } catch (PDOException $e) {
            echo "<p class='text-danger'>Error: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "</div>";
    echo "</div>";
    
    // Check specific problematic columns
    echo "<div class='card mt-4'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-exclamation-triangle me-2'></i>Problematic Columns Check</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    $problematicColumns = [
        'jaktfelt_competitions' => ['competition_date', 'max_participants', 'registration_start', 'registration_end', 'is_published', 'is_locked'],
        'jaktfelt_competition_categories' => ['max_participants']
    ];
    
    foreach ($problematicColumns as $table => $columns) {
        if (in_array($table, $tables)) {
            echo "<h6><code>$table</code></h6>";
            echo "<ul>";
            
            foreach ($columns as $column) {
                try {
                    $result = $pdo->query("SHOW COLUMNS FROM $table LIKE '$column'");
                    if ($result->rowCount() > 0) {
                        echo "<li class='text-success'><i class='fas fa-check me-2'></i>$column - EXISTS</li>";
                    } else {
                        echo "<li class='text-danger'><i class='fas fa-times me-2'></i>$column - MISSING</li>";
                    }
                } catch (PDOException $e) {
                    echo "<li class='text-danger'><i class='fas fa-times me-2'></i>$column - ERROR: " . $e->getMessage() . "</li>";
                }
            }
            
            echo "</ul>";
        } else {
            echo "<p class='text-warning'><code>$table</code> - Table does not exist</p>";
        }
    }
    
    echo "</div>";
    echo "</div>";
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo " <a href='check_tables.php' class='btn btn-info'><i class='fas fa-list me-2'></i>Check Tables</a>";
    echo " <a href='setup_database_ordered.php' class='btn btn-success'><i class='fas fa-database me-2'></i>Recreate Database</a>";
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
