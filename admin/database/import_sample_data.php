<?php
/**
 * Import Sample Data
 * Import sample data for testing
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Import Sample Data - Database Admin';
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
    echo "<h1><i class='fas fa-upload me-2'></i>Import Sample Data</h1>";
    echo "<p class='lead'>Import sample data for testing and development</p>";
    
    // Read sample data file
    $sampleDataFile = __DIR__ . '/../../database/sample_data.sql';
    if (!file_exists($sampleDataFile)) {
        echo "<div class='alert alert-danger'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Sample Data File Not Found</h4>";
        echo "<p>The sample data file <code>$sampleDataFile</code> does not exist.</p>";
        echo "</div>";
        echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
        echo "</div>";
        include_footer();
        exit;
    }
    
    $sampleData = file_get_contents($sampleDataFile);
    if ($sampleData === false) {
        echo "<div class='alert alert-danger'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Could Not Read Sample Data File</h4>";
        echo "<p>Failed to read the sample data file.</p>";
        echo "</div>";
        echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
        echo "</div>";
        include_footer();
        exit;
    }
    
    echo "<div class='alert alert-info'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Sample Data File</h5>";
    echo "<p>File: <code>$sampleDataFile</code></p>";
    echo "<p>Size: " . strlen($sampleData) . " characters</p>";
    echo "</div>";
    
    // Process the SQL file properly
    $statements = [];
    $currentStatement = '';
    $lines = explode("\n", $sampleData);
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Skip empty lines and comment-only lines
        if (empty($line) || preg_match('/^--/', $line)) {
            continue;
        }
        
        // Add line to current statement
        $currentStatement .= $line . ' ';
        
        // If line ends with semicolon, we have a complete statement
        if (substr($line, -1) === ';') {
            $statement = trim($currentStatement);
            if (!empty($statement)) {
                $statements[] = $statement;
            }
            $currentStatement = '';
        }
    }
    
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-cogs me-2'></i>Import Operations</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    echo "<p>Found " . count($statements) . " SQL statements to execute</p>";
    
    $importedCount = 0;
    $errorCount = 0;
    $skippedCount = 0;
    
    foreach ($statements as $index => $statement) {
        try {
            $result = $pdo->exec($statement);
            $importedCount++;
            echo "<p class='text-success'><i class='fas fa-check me-2'></i>Statement " . ($index + 1) . " executed successfully (affected rows: $result)</p>";
        } catch (PDOException $e) {
            $errorCount++;
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                echo "<p class='text-warning'><i class='fas fa-exclamation-triangle me-2'></i>Statement " . ($index + 1) . " skipped - duplicate entry</p>";
                $skippedCount++;
            } else if (strpos($e->getMessage(), 'Unknown column') !== false) {
                echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Statement " . ($index + 1) . " failed - missing column: " . $e->getMessage() . "</p>";
            } else {
                echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Statement " . ($index + 1) . " failed: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    echo "</div>";
    echo "</div>";
    
    // Summary
    echo "<div class='alert alert-info mt-4'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Import Summary</h5>";
    echo "<ul>";
    echo "<li><strong>Successfully executed:</strong> $importedCount statements</li>";
    echo "<li><strong>Errors:</strong> $errorCount statements</li>";
    echo "<li><strong>Skipped (duplicates):</strong> $skippedCount statements</li>";
    echo "</ul>";
    echo "</div>";
    
    if ($importedCount > 0) {
        echo "<div class='alert alert-success'>";
        echo "<h5><i class='fas fa-check me-2'></i>Sample Data Imported Successfully</h5>";
        echo "<p>Sample data has been imported into the database.</p>";
        echo "</div>";
    }
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo " <a href='check_data.php' class='btn btn-info'><i class='fas fa-chart-bar me-2'></i>Check Data</a>";
    if ($importedCount > 0) {
        echo " <a href='import_results.php' class='btn btn-primary'><i class='fas fa-trophy me-2'></i>Import Test Results</a>";
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
