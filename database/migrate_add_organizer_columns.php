<?php
/**
 * Migration: Add round_id and organizer_id to competitions table
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Core/Database.php';

global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "<h1>Migrasjon: Legg til round_id og organizer_id</h1>\n";
echo "<hr>\n";

// Check if round_id column exists
$roundIdExists = false;
try {
    $database->queryOne("SELECT round_id FROM jaktfelt_competitions LIMIT 1");
    $roundIdExists = true;
    echo "<p style='color: blue;'>ℹ️ round_id kolonne eksisterer allerede</p>\n";
} catch (Exception $e) {
    echo "<p style='color: orange;'>⚠️ round_id kolonne eksisterer ikke - legger til...</p>\n";
}

// Add round_id if not exists
if (!$roundIdExists) {
    try {
        $database->execute("ALTER TABLE jaktfelt_competitions ADD COLUMN round_id INT AFTER season_id");
        echo "<p style='color: green;'>✅ round_id kolonne lagt til</p>\n";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Kunne ikke legge til round_id: " . $e->getMessage() . "</p>\n";
    }
}

// Check if organizer_id column exists
$organizerIdExists = false;
try {
    $database->queryOne("SELECT organizer_id FROM jaktfelt_competitions LIMIT 1");
    $organizerIdExists = true;
    echo "<p style='color: blue;'>ℹ️ organizer_id kolonne eksisterer allerede</p>\n";
} catch (Exception $e) {
    echo "<p style='color: orange;'>⚠️ organizer_id kolonne eksisterer ikke - legger til...</p>\n";
}

// Add organizer_id if not exists
if (!$organizerIdExists) {
    try {
        $database->execute("ALTER TABLE jaktfelt_competitions ADD COLUMN organizer_id INT AFTER round_id");
        echo "<p style='color: green;'>✅ organizer_id kolonne lagt til</p>\n";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Kunne ikke legge til organizer_id: " . $e->getMessage() . "</p>\n";
    }
}

// Add indexes
try {
    $database->execute("ALTER TABLE jaktfelt_competitions ADD INDEX idx_round (round_id)");
    echo "<p style='color: green;'>✅ Index for round_id lagt til</p>\n";
} catch (Exception $e) {
    echo "<p style='color: orange;'>⚠️ Index for round_id: " . $e->getMessage() . "</p>\n";
}

try {
    $database->execute("ALTER TABLE jaktfelt_competitions ADD INDEX idx_organizer (organizer_id)");
    echo "<p style='color: green;'>✅ Index for organizer_id lagt til</p>\n";
} catch (Exception $e) {
    echo "<p style='color: orange;'>⚠️ Index for organizer_id: " . $e->getMessage() . "</p>\n";
}

echo "<hr>\n";
echo "<h2>✅ Migrasjon fullført!</h2>\n";
echo "<p><a href='" . base_url('debug_organizers.php') . "' class='btn btn-primary'>Test med debug_organizers.php</a></p>\n";
?>

