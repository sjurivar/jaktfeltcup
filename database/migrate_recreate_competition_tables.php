<?php
/**
 * Migration: Recreate competition-related tables with correct structure
 * This will DROP and recreate:
 * - jaktfelt_rounds
 * - jaktfelt_organizers
 * - jaktfelt_competitions (with updated foreign keys)
 * 
 * WARNING: This will delete all existing data in these tables!
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Core/Database.php';

global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "<h1>Migrasjon: Gjenskaper konkurransetabeller</h1>\n";
echo "<hr>\n";

// Step 1: Drop existing tables (in correct order due to foreign keys)
echo "<h2>Steg 1: Sletter eksisterende tabeller...</h2>\n";

try {
    $database->execute("SET FOREIGN_KEY_CHECKS = 0");
    echo "<p style='color: blue;'>ℹ️ Deaktiverte foreign key checks</p>\n";
    
    $database->execute("DROP TABLE IF EXISTS jaktfelt_competitions");
    echo "<p style='color: green;'>✅ Slettet jaktfelt_competitions</p>\n";
    
    $database->execute("DROP TABLE IF EXISTS jaktfelt_rounds");
    echo "<p style='color: green;'>✅ Slettet jaktfelt_rounds</p>\n";
    
    $database->execute("DROP TABLE IF EXISTS jaktfelt_organizers");
    echo "<p style='color: green;'>✅ Slettet jaktfelt_organizers</p>\n";
    
    $database->execute("SET FOREIGN_KEY_CHECKS = 1");
    echo "<p style='color: blue;'>ℹ️ Aktiverte foreign key checks</p>\n";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil ved sletting: " . $e->getMessage() . "</p>\n";
    exit;
}

// Step 2: Create jaktfelt_rounds table
echo "<h2>Steg 2: Oppretter jaktfelt_rounds tabell...</h2>\n";

try {
    $sql = "CREATE TABLE jaktfelt_rounds (
        id INT PRIMARY KEY AUTO_INCREMENT,
        season_id INT NOT NULL,
        round_number INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        result_deadline DATETIME NOT NULL,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        is_test_data BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (season_id) REFERENCES jaktfelt_seasons(id) ON DELETE CASCADE,
        INDEX idx_season (season_id),
        INDEX idx_round_number (round_number),
        INDEX idx_dates (start_date, end_date),
        INDEX idx_active (is_active),
        INDEX idx_test_data (is_test_data),
        UNIQUE KEY unique_season_round (season_id, round_number)
    )";
    
    $database->execute($sql);
    echo "<p style='color: green;'>✅ jaktfelt_rounds tabell opprettet</p>\n";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil ved oppretting av jaktfelt_rounds: " . $e->getMessage() . "</p>\n";
    exit;
}

// Step 3: Create jaktfelt_organizers table
echo "<h2>Steg 3: Oppretter jaktfelt_organizers tabell...</h2>\n";

try {
    $sql = "CREATE TABLE jaktfelt_organizers (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(200) NOT NULL,
        organization_type ENUM('club', 'range', 'individual', 'other') DEFAULT 'club',
        contact_person VARCHAR(200),
        email VARCHAR(200),
        phone VARCHAR(20),
        city VARCHAR(100),
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        is_test_data BOOLEAN DEFAULT FALSE,
        INDEX idx_name (name),
        INDEX idx_city (city),
        INDEX idx_active (is_active),
        INDEX idx_organization_type (organization_type),
        INDEX idx_test_data (is_test_data)
    )";
    
    $database->execute($sql);
    echo "<p style='color: green;'>✅ jaktfelt_organizers tabell opprettet</p>\n";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil ved oppretting av jaktfelt_organizers: " . $e->getMessage() . "</p>\n";
    exit;
}

// Step 4: Create jaktfelt_competitions table
echo "<h2>Steg 4: Oppretter jaktfelt_competitions tabell...</h2>\n";

try {
    $sql = "CREATE TABLE jaktfelt_competitions (
        id INT PRIMARY KEY AUTO_INCREMENT,
        season_id INT NOT NULL,
        round_id INT NULL,
        organizer_id INT NULL,
        name VARCHAR(100) NOT NULL,
        description TEXT,
        location VARCHAR(100) NOT NULL,
        competition_date DATE NOT NULL,
        registration_start DATE NOT NULL,
        registration_end DATE NOT NULL,
        max_participants INT,
        is_published BOOLEAN DEFAULT FALSE,
        is_locked BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        is_test_data BOOLEAN DEFAULT FALSE,
        FOREIGN KEY (season_id) REFERENCES jaktfelt_seasons(id) ON DELETE CASCADE,
        FOREIGN KEY (round_id) REFERENCES jaktfelt_rounds(id) ON DELETE SET NULL,
        FOREIGN KEY (organizer_id) REFERENCES jaktfelt_organizers(id) ON DELETE SET NULL,
        INDEX idx_season (season_id),
        INDEX idx_round (round_id),
        INDEX idx_organizer (organizer_id),
        INDEX idx_date (competition_date),
        INDEX idx_registration (registration_start, registration_end),
        INDEX idx_published (is_published),
        INDEX idx_test_data (is_test_data)
    )";
    
    $database->execute($sql);
    echo "<p style='color: green;'>✅ jaktfelt_competitions tabell opprettet</p>\n";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil ved oppretting av jaktfelt_competitions: " . $e->getMessage() . "</p>\n";
    exit;
}

// Step 5: Insert sample data
echo "<h2>Steg 5: Legger inn eksempeldata...</h2>\n";

try {
    // Get current season
    $season = $database->queryOne("SELECT id FROM jaktfelt_seasons WHERE is_active = 1 ORDER BY created_at DESC LIMIT 1");
    
    if (!$season) {
        // Create a season if none exists
        $database->execute("INSERT INTO jaktfelt_seasons (name, year, is_active, start_date, end_date, is_test_data) 
            VALUES ('Nasjonal 15m Jaktfeltcup 2024-2025', 2024, TRUE, '2024-11-01', '2025-02-28', FALSE)");
        $season = $database->queryOne("SELECT id FROM jaktfelt_seasons WHERE year = 2024");
        echo "<p style='color: green;'>✅ Opprettet sesong 2024-2025</p>\n";
    }
    
    $seasonId = $season['id'];
    echo "<p style='color: blue;'>ℹ️ Bruker sesong ID: {$seasonId}</p>\n";
    
    // Insert rounds
    $rounds = [
        ['round_number' => 1, 'name' => 'Runde 1', 'start_date' => '2024-11-01', 'end_date' => '2024-11-30', 'result_deadline' => '2024-12-05 23:59:59'],
        ['round_number' => 2, 'name' => 'Runde 2', 'start_date' => '2024-12-01', 'end_date' => '2024-12-31', 'result_deadline' => '2025-01-05 23:59:59'],
        ['round_number' => 3, 'name' => 'Runde 3', 'start_date' => '2025-01-01', 'end_date' => '2025-01-31', 'result_deadline' => '2025-02-05 23:59:59'],
        ['round_number' => 4, 'name' => 'Runde 4 + Finale', 'start_date' => '2025-02-01', 'end_date' => '2025-02-28', 'result_deadline' => '2025-03-05 23:59:59']
    ];
    
    foreach ($rounds as $round) {
        $database->execute(
            "INSERT INTO jaktfelt_rounds (season_id, round_number, name, start_date, end_date, result_deadline, is_test_data) 
            VALUES (?, ?, ?, ?, ?, ?, FALSE)",
            [$seasonId, $round['round_number'], $round['name'], $round['start_date'], $round['end_date'], $round['result_deadline']]
        );
        echo "<p style='color: green;'>✅ Lagt til {$round['name']}</p>\n";
    }
    
    // Insert organizers
    $organizers = [
        ['name' => 'Leikanger Skytterlag', 'type' => 'club', 'person' => 'Anne Birgitta Sollie', 'email' => 'leikanger@skytterlag.no', 'phone' => '0', 'city' => 'Leikanger'],
        ['name' => 'Oslo Feltskyttere', 'type' => 'club', 'person' => 'Per Hansen', 'email' => 'oslo@feltskyttere.no', 'phone' => '0', 'city' => 'Oslo'],
        ['name' => 'Bergen Skytterlag', 'type' => 'club', 'person' => 'Kari Nilsen', 'email' => 'bergen@skytterlag.no', 'phone' => '0', 'city' => 'Bergen'],
        ['name' => 'Trondheim Jegerforening', 'type' => 'club', 'person' => 'Ole Olsen', 'email' => 'trondheim@jeger.no', 'phone' => '0', 'city' => 'Trondheim']
    ];
    
    foreach ($organizers as $org) {
        $database->execute(
            "INSERT INTO jaktfelt_organizers (name, organization_type, contact_person, email, phone, city, is_test_data) 
            VALUES (?, ?, ?, ?, ?, ?, FALSE)",
            [$org['name'], $org['type'], $org['person'], $org['email'], $org['phone'], $org['city']]
        );
        echo "<p style='color: green;'>✅ Lagt til arrangør: {$org['name']}</p>\n";
    }
    
    // Insert sample competitions
    $round1 = $database->queryOne("SELECT id FROM jaktfelt_rounds WHERE season_id = ? AND round_number = 1", [$seasonId]);
    $org1 = $database->queryOne("SELECT id FROM jaktfelt_organizers WHERE name = 'Leikanger Skytterlag'");
    $org2 = $database->queryOne("SELECT id FROM jaktfelt_organizers WHERE name = 'Oslo Feltskyttere'");
    
    if ($round1 && $org1) {
        $database->execute(
            "INSERT INTO jaktfelt_competitions 
            (season_id, round_id, organizer_id, name, description, location, competition_date, registration_start, registration_end, max_participants, is_published, is_test_data) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, FALSE)",
            [
                $seasonId, 
                $round1['id'], 
                $org1['id'],
                'Runde 1 - Leikanger',
                'Første stevne i Nasjonal 15m Jaktfeltcup 2024-2025',
                'Leikanger Skytterbane',
                '2024-11-15',
                '2024-10-15',
                '2024-11-10',
                50
            ]
        );
        echo "<p style='color: green;'>✅ Lagt til stevne: Runde 1 - Leikanger</p>\n";
    }
    
    if ($round1 && $org2) {
        $database->execute(
            "INSERT INTO jaktfelt_competitions 
            (season_id, round_id, organizer_id, name, description, location, competition_date, registration_start, registration_end, max_participants, is_published, is_test_data) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE, FALSE)",
            [
                $seasonId, 
                $round1['id'], 
                $org2['id'],
                'Runde 1 - Oslo',
                'Første stevne i Oslo',
                'Oslo Skytesenter',
                '2024-11-22',
                '2024-10-22',
                '2024-11-17',
                60
            ]
        );
        echo "<p style='color: green;'>✅ Lagt til stevne: Runde 1 - Oslo</p>\n";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil ved innsetting av eksempeldata: " . $e->getMessage() . "</p>\n";
}

echo "<hr>\n";
echo "<h2>✅ Migrasjon fullført!</h2>\n";
echo "<p><strong>Neste steg:</strong></p>\n";
echo "<ul>\n";
echo "<li><a href='" . base_url('debug_organizers.php') . "'>Test med debug_organizers.php</a></li>\n";
echo "<li><a href='" . base_url('arrangor') . "'>Se arrangørsiden</a></li>\n";
echo "<li><a href='" . base_url('admin/content/organizers') . "'>Administrer arrangører</a></li>\n";
echo "</ul>\n";
?>

