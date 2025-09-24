<?php
/**
 * Import Sample Data (Fixed)
 * Import sample data with proper error handling and column checking
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Import Sample Data (Fixed) - Database Admin';
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
    echo "<h1><i class='fas fa-upload me-2'></i>Import Sample Data (Fixed)</h1>";
    echo "<p class='lead'>Import sample data with proper error handling</p>";
    
    // Check database structure first
    echo "<div class='card mb-4'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-cogs me-2'></i>Database Structure Check</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    $tables = ['jaktfelt_seasons', 'jaktfelt_categories', 'jaktfelt_users', 'jaktfelt_competitions', 'jaktfelt_competition_categories', 'jaktfelt_registrations', 'jaktfelt_results', 'jaktfelt_point_systems', 'jaktfelt_point_rules', 'jaktfelt_season_point_systems'];
    
    $missingTables = [];
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($result->rowCount() == 0) {
            $missingTables[] = $table;
        }
    }
    
    if (!empty($missingTables)) {
        echo "<div class='alert alert-danger'>";
        echo "<h6>Missing Tables:</h6>";
        echo "<ul>";
        foreach ($missingTables as $table) {
            echo "<li><code>$table</code></li>";
        }
        echo "</ul>";
        echo "<p>Please run <a href='setup_database_ordered.php'>Setup Database (Ordered)</a> first.</p>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
        echo "</div>";
        include_footer();
        exit;
    }
    
    // Check column structure
    $competitionColumns = $pdo->query("SHOW COLUMNS FROM jaktfelt_competitions")->fetchAll(PDO::FETCH_COLUMN);
    $hasCompetitionDate = in_array('competition_date', $competitionColumns);
    $hasMaxParticipants = in_array('max_participants', $competitionColumns);
    
    echo "<p><strong>jaktfelt_competitions columns:</strong></p>";
    echo "<ul>";
    echo "<li>competition_date: " . ($hasCompetitionDate ? '✅' : '❌') . "</li>";
    echo "<li>max_participants: " . ($hasMaxParticipants ? '✅' : '❌') . "</li>";
    echo "</ul>";
    
    if (!$hasCompetitionDate || !$hasMaxParticipants) {
        echo "<div class='alert alert-warning'>";
        echo "<p>Some columns are missing. The database structure may be outdated.</p>";
        echo "<p>Please run <a href='setup_database_ordered.php'>Setup Database (Ordered)</a> to recreate with correct structure.</p>";
        echo "</div>";
    }
    
    echo "</div>";
    echo "</div>";
    
    // Import data in correct order
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-cogs me-2'></i>Data Import (Ordered)</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    $importedCount = 0;
    $errorCount = 0;
    
    // 1. Seasons
    try {
        $stmt = $pdo->prepare("INSERT INTO jaktfelt_seasons (name, year, is_active, start_date, end_date, is_test_data) VALUES (?, ?, ?, ?, ?, ?)");
        $seasons = [
            ['Jaktfeltcup 2023 (Test)', 2023, false, '2023-01-01', '2023-12-31', true],
            ['Jaktfeltcup 2024 (Test)', 2024, true, '2024-01-01', '2024-12-31', true]
        ];
        
        foreach ($seasons as $season) {
            $stmt->execute($season);
            $importedCount++;
        }
        echo "<p class='text-success'><i class='fas fa-check me-2'></i>Imported 2 seasons</p>";
    } catch (PDOException $e) {
        $errorCount++;
        echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error importing seasons: " . $e->getMessage() . "</p>";
    }
    
    // 2. Categories
    try {
        $stmt = $pdo->prepare("INSERT INTO jaktfelt_categories (name, description, min_age, max_age, weapon_type, is_active, is_test_data) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $categories = [
            ['Senior (Test)', 'Senior klasse - Testdata', 18, 99, 'Rifle', true, true],
            ['Junior (Test)', 'Junior klasse - Testdata', 12, 17, 'Rifle', true, true],
            ['Veteran (Test)', 'Veteran klasse - Testdata', 50, 99, 'Rifle', true, true],
            ['Dame (Test)', 'Dame klasse - Testdata', 18, 99, 'Rifle', true, true]
        ];
        
        foreach ($categories as $category) {
            $stmt->execute($category);
            $importedCount++;
        }
        echo "<p class='text-success'><i class='fas fa-check me-2'></i>Imported 4 categories</p>";
    } catch (PDOException $e) {
        $errorCount++;
        echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error importing categories: " . $e->getMessage() . "</p>";
    }
    
    // 3. Users
    try {
        $stmt = $pdo->prepare("INSERT INTO jaktfelt_users (username, email, password_hash, first_name, last_name, phone, date_of_birth, address, role, is_active, email_verified, is_test_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $users = [
            ['testadmin', 'admin@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test', 'Administrator', '12345678', '1980-01-01', 'Testveien 1, 0001 Oslo', 'admin', true, true, true],
            ['testorganizer', 'organizer@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test', 'Arrangør', '12345679', '1975-05-15', 'Arrangørveien 2, 0002 Oslo', 'organizer', true, true, true],
            ['testdeltaker1', 'deltaker1@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ole', 'Hansen', '12345680', '1990-03-20', 'Deltakerveien 3, 0003 Oslo', 'participant', true, true, true],
            ['testdeltaker2', 'deltaker2@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kari', 'Nordmann', '12345681', '1985-07-10', 'Deltakerveien 4, 0004 Oslo', 'participant', true, true, true],
            ['testdeltaker3', 'deltaker3@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Erik', 'Johansen', '12345682', '1992-11-05', 'Deltakerveien 5, 0005 Oslo', 'participant', true, true, true],
            ['testdeltaker4', 'deltaker4@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Anna', 'Larsen', '12345683', '1988-09-15', 'Deltakerveien 6, 0006 Oslo', 'participant', true, true, true],
            ['testdeltaker5', 'deltaker5@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lars', 'Andersen', '12345684', '1995-01-30', 'Deltakerveien 7, 0007 Oslo', 'participant', true, true, true],
            ['testdeltaker6', 'deltaker6@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ingrid', 'Pedersen', '12345685', '1983-06-25', 'Deltakerveien 8, 0008 Oslo', 'participant', true, true, true],
            ['testdeltaker7', 'deltaker7@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bjørn', 'Olsen', '12345686', '1991-12-12', 'Deltakerveien 9, 0009 Oslo', 'participant', true, true, true],
            ['testdeltaker8', 'deltaker8@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Marianne', 'Haugen', '12345687', '1987-04-18', 'Deltakerveien 10, 0010 Oslo', 'participant', true, true, true],
            ['testdeltaker9', 'deltaker9@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tom', 'Berg', '12345688', '1993-08-22', 'Deltakerveien 11, 0011 Oslo', 'participant', true, true, true],
            ['testdeltaker10', 'deltaker10@test.jaktfeltcup.no', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sofie', 'Kristiansen', '12345689', '1989-10-08', 'Deltakerveien 12, 0012 Oslo', 'participant', true, true, true]
        ];
        
        foreach ($users as $user) {
            $stmt->execute($user);
            $importedCount++;
        }
        echo "<p class='text-success'><i class='fas fa-check me-2'></i>Imported 12 users</p>";
    } catch (PDOException $e) {
        $errorCount++;
        echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error importing users: " . $e->getMessage() . "</p>";
    }
    
    // 4. Point Systems
    try {
        $stmt = $pdo->prepare("INSERT INTO jaktfelt_point_systems (name, description, is_active, is_test_data) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Standard Poengsystem (Test)', 'Standard poengsystem for jaktfeltcup - Testdata', true, true]);
        $importedCount++;
        echo "<p class='text-success'><i class='fas fa-check me-2'></i>Imported 1 point system</p>";
    } catch (PDOException $e) {
        $errorCount++;
        echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error importing point systems: " . $e->getMessage() . "</p>";
    }
    
    // 5. Competitions (with correct column names)
    try {
        if ($hasCompetitionDate && $hasMaxParticipants) {
            $stmt = $pdo->prepare("INSERT INTO jaktfelt_competitions (season_id, name, description, location, competition_date, registration_start, registration_end, max_participants, is_published, is_locked, organizer_id, is_test_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        } else {
            $stmt = $pdo->prepare("INSERT INTO jaktfelt_competitions (season_id, name, description, location, date, registration_deadline, status, organizer_id, is_test_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        }
        
        $competitions = [
            [2, 'Vårstevnet 2024 (Test)', 'Første stevne i sesongen - Testdata', 'Oslo Skytebane', '2024-04-15', '2024-03-01', '2024-04-10', 50, true, false, 2, true],
            [2, 'Sommerstevnet 2024 (Test)', 'Andre stevne i sesongen - Testdata', 'Bergen Skytebane', '2024-06-20', '2024-05-01', '2024-06-15', 60, true, false, 2, true],
            [2, 'Høststevnet 2024 (Test)', 'Tredje stevne i sesongen - Testdata', 'Trondheim Skytebane', '2024-09-10', '2024-08-01', '2024-09-05', 55, true, false, 2, true],
            [2, 'Vinterstevnet 2024 (Test)', 'Avsluttende stevne i sesongen - Testdata', 'Stavanger Skytebane', '2024-11-25', '2024-10-01', '2024-11-20', 45, true, false, 2, true]
        ];
        
        foreach ($competitions as $competition) {
            if ($hasCompetitionDate && $hasMaxParticipants) {
                $stmt->execute($competition);
            } else {
                // Simplified version for old schema
                $stmt->execute([
                    $competition[0], // season_id
                    $competition[1], // name
                    $competition[2], // description
                    $competition[3], // location
                    $competition[4], // date
                    $competition[6], // registration_deadline
                    'upcoming',      // status
                    $competition[10], // organizer_id
                    $competition[11]  // is_test_data
                ]);
            }
            $importedCount++;
        }
        echo "<p class='text-success'><i class='fas fa-check me-2'></i>Imported 4 competitions</p>";
    } catch (PDOException $e) {
        $errorCount++;
        echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error importing competitions: " . $e->getMessage() . "</p>";
    }
    
    // 6. Competition Categories
    try {
        $stmt = $pdo->prepare("INSERT INTO jaktfelt_competition_categories (competition_id, category_id, is_test_data) VALUES (?, ?, ?)");
        $competitionCategories = [
            [1, 1, true], [1, 2, true], [1, 3, true], [1, 4, true],
            [2, 1, true], [2, 2, true], [2, 3, true], [2, 4, true],
            [3, 1, true], [3, 2, true], [3, 3, true], [3, 4, true],
            [4, 1, true], [4, 2, true], [4, 3, true], [4, 4, true]
        ];
        
        foreach ($competitionCategories as $cc) {
            $stmt->execute($cc);
            $importedCount++;
        }
        echo "<p class='text-success'><i class='fas fa-check me-2'></i>Imported 16 competition categories</p>";
    } catch (PDOException $e) {
        $errorCount++;
        echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error importing competition categories: " . $e->getMessage() . "</p>";
    }
    
    // 7. Point Rules
    try {
        $stmt = $pdo->prepare("INSERT INTO jaktfelt_point_rules (point_system_id, position, points, is_test_data) VALUES (?, ?, ?, ?)");
        $pointRules = [
            [1, 1, 100, true], [1, 2, 90, true], [1, 3, 80, true], [1, 4, 70, true], [1, 5, 60, true],
            [1, 6, 50, true], [1, 7, 40, true], [1, 8, 30, true], [1, 9, 20, true], [1, 10, 10, true]
        ];
        
        foreach ($pointRules as $pr) {
            $stmt->execute($pr);
            $importedCount++;
        }
        echo "<p class='text-success'><i class='fas fa-check me-2'></i>Imported 10 point rules</p>";
    } catch (PDOException $e) {
        $errorCount++;
        echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error importing point rules: " . $e->getMessage() . "</p>";
    }
    
    // 8. Season Point Systems
    try {
        $stmt = $pdo->prepare("INSERT INTO jaktfelt_season_point_systems (season_id, point_system_id, is_test_data) VALUES (?, ?, ?)");
        $stmt->execute([2, 1, true]);
        $importedCount++;
        echo "<p class='text-success'><i class='fas fa-check me-2'></i>Imported 1 season point system</p>";
    } catch (PDOException $e) {
        $errorCount++;
        echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error importing season point systems: " . $e->getMessage() . "</p>";
    }
    
    echo "</div>";
    echo "</div>";
    
    // Summary
    echo "<div class='alert alert-info mt-4'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Import Summary</h5>";
    echo "<ul>";
    echo "<li><strong>Records imported:</strong> $importedCount</li>";
    echo "<li><strong>Errors:</strong> $errorCount</li>";
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
