<?php
/**
 * Setup Database Schema
 * Create database and import schema
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Setup Database - Database Admin';
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
    // Connect to MySQL (without database)
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='container mt-4'>";
    echo "<h1><i class='fas fa-database me-2'></i>Setup Database Schema</h1>";
    echo "<p class='lead'>Create database and import schema</p>";
    
    // Check if database exists
    $result = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    if ($result->rowCount() > 0) {
        echo "<div class='alert alert-warning'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Database Already Exists</h4>";
        echo "<p>Database <code>$dbname</code> already exists.</p>";
        echo "</div>";
    } else {
        // Create database
        $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "<div class='alert alert-success'>";
        echo "<h4><i class='fas fa-check me-2'></i>Database Created</h4>";
        echo "<p>Database <code>$dbname</code> has been created successfully.</p>";
        echo "</div>";
    }
    
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute schema
    $schemaFile = __DIR__ . '/../../database/schema.sql';
    if (!file_exists($schemaFile)) {
        echo "<div class='alert alert-danger'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Schema File Not Found</h4>";
        echo "<p>The schema file <code>$schemaFile</code> does not exist.</p>";
        echo "</div>";
        echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
        echo "</div>";
        include_footer();
        exit;
    }
    
    $schema = file_get_contents($schemaFile);
    if ($schema === false) {
        echo "<div class='alert alert-danger'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Could Not Read Schema File</h4>";
        echo "<p>Failed to read the schema file.</p>";
        echo "</div>";
        echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
        echo "</div>";
        include_footer();
        exit;
    }
    
    echo "<div class='alert alert-info'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Schema File</h5>";
    echo "<p>File: <code>$schemaFile</code></p>";
    echo "<p>Size: " . strlen($schema) . " characters</p>";
    echo "</div>";
    
    // Execute schema with foreign key handling
    $statements = explode(';', $schema);
    $executedCount = 0;
    $errorCount = 0;
    
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-cogs me-2'></i>Schema Execution</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    // Disable foreign key checks for initial table creation
    echo "<p class='text-info'><i class='fas fa-info-circle me-2'></i>Disabling foreign key checks for table creation...</p>";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    foreach ($statements as $index => $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            try {
                $pdo->exec($statement);
                $executedCount++;
                echo "<p class='text-success'><i class='fas fa-check me-2'></i>Statement " . ($index + 1) . " executed successfully</p>";
            } catch (PDOException $e) {
                $errorCount++;
                echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Statement " . ($index + 1) . " failed: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    // Re-enable foreign key checks
    echo "<p class='text-info'><i class='fas fa-info-circle me-2'></i>Re-enabling foreign key checks...</p>";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "</div>";
    echo "</div>";
    
    // Summary
    echo "<div class='alert alert-info mt-4'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Schema Execution Summary</h5>";
    echo "<ul>";
    echo "<li><strong>Successfully executed:</strong> $executedCount statements</li>";
    echo "<li><strong>Errors:</strong> $errorCount statements</li>";
    echo "</ul>";
    echo "</div>";
    
    if ($executedCount > 0) {
        echo "<div class='alert alert-success'>";
        echo "<h5><i class='fas fa-check me-2'></i>Database Schema Setup Complete</h5>";
        echo "<p>Database schema has been created successfully.</p>";
        echo "</div>";
    }
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo " <a href='check_tables.php' class='btn btn-info'><i class='fas fa-list me-2'></i>Check Tables</a>";
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
