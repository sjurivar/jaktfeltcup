<?php
/**
 * Sample Data Setup Script with Debug Information
 * Run this to import sample data for testing
 */

echo "<h2>Jaktfeltcup Sample Data Setup (Debug Mode)</h2>";

// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'jaktfeltcup';

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to database</p>";
    
    // Check if sample data file exists
    $sampleDataFile = __DIR__ . '/database/sample_data.sql';
    echo "<p>📁 Sample data file: $sampleDataFile</p>";
    
    if (!file_exists($sampleDataFile)) {
        echo "<p>❌ Sample data file does not exist!</p>";
        exit;
    }
    
    $fileSize = filesize($sampleDataFile);
    echo "<p>📊 File size: $fileSize bytes</p>";
    
    // Read and execute sample data
    $sampleData = file_get_contents($sampleDataFile);
    if ($sampleData === false) {
        echo "<p>❌ Could not read sample data file</p>";
        exit;
    }
    
    echo "<p>📄 File content length: " . strlen($sampleData) . " characters</p>";
    
    // Show first 200 characters of file
    echo "<p>📝 First 200 characters of file:</p>";
    echo "<pre>" . htmlspecialchars(substr($sampleData, 0, 200)) . "...</pre>";
    
    // Split by semicolon and execute each statement
    $statements = explode(';', $sampleData);
    echo "<p>🔢 Found " . count($statements) . " statements (including empty ones)</p>";
    
    $importedCount = 0;
    $errorCount = 0;
    $skippedCount = 0;
    
    foreach ($statements as $index => $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            echo "<p>🔄 Executing statement " . ($index + 1) . ": " . substr($statement, 0, 50) . "...</p>";
            
            try {
                $result = $pdo->exec($statement);
                $importedCount++;
                echo "<p>✅ Success (affected rows: $result)</p>";
            } catch (PDOException $e) {
                $errorCount++;
                echo "<p>❌ Error: " . $e->getMessage() . "</p>";
                
                // Check for specific errors
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    echo "<p>⚠️ Duplicate entry - skipping</p>";
                    $skippedCount++;
                } else if (strpos($e->getMessage(), 'Unknown column') !== false) {
                    echo "<p>❌ Missing column - need to run migration first</p>";
                    echo "<p>💡 Run <a href='fix_database_structure.php'>fix_database_structure.php</a> first</p>";
                } else {
                    echo "<p>❌ Other error - check statement</p>";
                }
            }
        } else {
            echo "<p>⏭️ Skipping empty or comment statement " . ($index + 1) . "</p>";
        }
    }
    
    echo "<h3>📊 Execution Summary:</h3>";
    echo "<ul>";
    echo "<li>✅ Successfully executed: $importedCount statements</li>";
    echo "<li>❌ Errors: $errorCount statements</li>";
    echo "<li>⏭️ Skipped: $skippedCount statements</li>";
    echo "</ul>";
    
    // Show summary of what's in database
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
    
    echo "<h3>📋 Database Content Summary:</h3>";
    echo "<ul>";
    foreach ($tables as $table => $name) {
        try {
            // Check if table exists
            $result = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($result->rowCount() == 0) {
                echo "<li><strong>$name:</strong> Table does not exist</li>";
                continue;
            }
            
            // Check if is_test_data column exists
            $result = $pdo->query("SHOW COLUMNS FROM $table LIKE 'is_test_data'");
            if ($result->rowCount() == 0) {
                echo "<li><strong>$name:</strong> Table exists but missing is_test_data column</li>";
                continue;
            }
            
            // Count test data
            $count = $pdo->query("SELECT COUNT(*) FROM $table WHERE is_test_data = TRUE")->fetchColumn();
            $totalCount = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "<li><strong>$name:</strong> $count test records (total: $totalCount)</li>";
        } catch (PDOException $e) {
            echo "<li><strong>$name:</strong> Error checking table: " . $e->getMessage() . "</li>";
        }
    }
    echo "</ul>";
    
    if ($importedCount > 0) {
        echo "<h3>👥 Test Users:</h3>";
        echo "<ul>";
        echo "<li><strong>Admin:</strong> testadmin / password</li>";
        echo "<li><strong>Organizer:</strong> testorganizer / password</li>";
        echo "<li><strong>Participants:</strong> testdeltaker1-10 / password</li>";
        echo "</ul>";
        
        echo "<p><strong>✅ Sample data setup complete!</strong></p>";
        echo "<p><a href='/jaktfeltcup/'>Go to Jaktfeltcup</a></p>";
    } else {
        echo "<p><strong>❌ No data was imported. Please check the errors above.</strong></p>";
        echo "<p>💡 Try running <a href='fix_database_structure.php'>fix_database_structure.php</a> first</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Database Error: " . $e->getMessage() . "</p>";
    echo "<p>Make sure the database is set up first by running <a href='setup_database.php'>setup_database.php</a></p>";
}
?>
