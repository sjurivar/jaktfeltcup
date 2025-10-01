<?php
/**
 * Debug organizers and rounds data
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Helpers/OrganizerHelper.php';

global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "<h1>Debug Arrangører og Runder</h1>\n";
echo "<hr>\n";

// Test 1: Check if tables exist
echo "<h2>1. Sjekk om tabeller eksisterer</h2>\n";

$tables = ['jaktfelt_seasons', 'jaktfelt_rounds', 'jaktfelt_organizers', 'jaktfelt_competitions'];

foreach ($tables as $table) {
    try {
        $result = $database->queryOne("SELECT COUNT(*) as count FROM $table");
        echo "<p style='color: green;'>✅ <strong>$table:</strong> " . $result['count'] . " rader</p>\n";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ <strong>$table:</strong> " . $e->getMessage() . "</p>\n";
    }
}

echo "<hr>\n";

// Test 2: Check seasons
echo "<h2>2. Sjekk sesonger</h2>\n";

try {
    $seasons = $database->queryAll("SELECT * FROM jaktfelt_seasons ORDER BY year DESC");
    
    if (!empty($seasons)) {
        echo "<table border='1' cellpadding='5'>\n";
        echo "<tr><th>ID</th><th>Navn</th><th>År</th><th>Aktiv</th><th>Start</th><th>Slutt</th></tr>\n";
        foreach ($seasons as $season) {
            echo "<tr>";
            echo "<td>" . $season['id'] . "</td>";
            echo "<td>" . htmlspecialchars($season['name']) . "</td>";
            echo "<td>" . $season['year'] . "</td>";
            echo "<td>" . ($season['is_active'] ? 'Ja' : 'Nei') . "</td>";
            echo "<td>" . $season['start_date'] . "</td>";
            echo "<td>" . $season['end_date'] . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    } else {
        echo "<p style='color: orange;'>⚠️ Ingen sesonger funnet</p>\n";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil: " . $e->getMessage() . "</p>\n";
}

echo "<hr>\n";

// Test 3: Check rounds
echo "<h2>3. Sjekk runder</h2>\n";

try {
    $rounds = $database->queryAll("SELECT * FROM jaktfelt_rounds ORDER BY round_number");
    
    if (!empty($rounds)) {
        echo "<table border='1' cellpadding='5'>\n";
        echo "<tr><th>ID</th><th>Sesong</th><th>Runde</th><th>Navn</th><th>Start</th><th>Slutt</th><th>Frist</th><th>Aktiv</th></tr>\n";
        foreach ($rounds as $round) {
            echo "<tr>";
            echo "<td>" . $round['id'] . "</td>";
            echo "<td>" . $round['season_id'] . "</td>";
            echo "<td>" . $round['round_number'] . "</td>";
            echo "<td>" . htmlspecialchars($round['name']) . "</td>";
            echo "<td>" . $round['start_date'] . "</td>";
            echo "<td>" . $round['end_date'] . "</td>";
            echo "<td>" . $round['result_deadline'] . "</td>";
            echo "<td>" . ($round['is_active'] ? 'Ja' : 'Nei') . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    } else {
        echo "<p style='color: orange;'>⚠️ Ingen runder funnet</p>\n";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil: " . $e->getMessage() . "</p>\n";
}

echo "<hr>\n";

// Test 4: Check organizers
echo "<h2>4. Sjekk arrangører</h2>\n";

try {
    $organizers = $database->queryAll("SELECT * FROM jaktfelt_organizers WHERE is_active = 1");
    
    if (!empty($organizers)) {
        echo "<table border='1' cellpadding='5'>\n";
        echo "<tr><th>ID</th><th>Navn</th><th>Type</th><th>Kontakt</th><th>E-post</th><th>Telefon</th><th>By</th></tr>\n";
        foreach ($organizers as $org) {
            echo "<tr>";
            echo "<td>" . $org['id'] . "</td>";
            echo "<td>" . htmlspecialchars($org['name']) . "</td>";
            echo "<td>" . htmlspecialchars($org['organization_type']) . "</td>";
            echo "<td>" . htmlspecialchars($org['contact_person'] ?? '-') . "</td>";
            echo "<td>" . htmlspecialchars($org['email'] ?? '-') . "</td>";
            echo "<td>" . htmlspecialchars($org['phone'] ?? '-') . "</td>";
            echo "<td>" . htmlspecialchars($org['city'] ?? '-') . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    } else {
        echo "<p style='color: orange;'>⚠️ Ingen arrangører funnet</p>\n";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil: " . $e->getMessage() . "</p>\n";
}

echo "<hr>\n";

// Test 5: Check competitions
echo "<h2>5. Sjekk stevner</h2>\n";

try {
    $competitions = $database->queryAll(
        "SELECT c.*, r.round_number, r.name as round_name, o.name as organizer_name 
         FROM jaktfelt_competitions c
         LEFT JOIN jaktfelt_rounds r ON c.round_id = r.id
         LEFT JOIN jaktfelt_organizers o ON c.organizer_id = o.id
         ORDER BY c.competition_date"
    );
    
    if (!empty($competitions)) {
        echo "<p><strong>Antall stevner:</strong> " . count($competitions) . "</p>\n";
        echo "<table border='1' cellpadding='5' style='font-size: 12px;'>\n";
        echo "<tr><th>ID</th><th>Navn</th><th>Dato</th><th>Runde</th><th>Arrangør</th><th>Sted</th></tr>\n";
        foreach ($competitions as $comp) {
            echo "<tr>";
            echo "<td>" . $comp['id'] . "</td>";
            echo "<td>" . htmlspecialchars($comp['name']) . "</td>";
            echo "<td>" . $comp['competition_date'] . "</td>";
            echo "<td>" . ($comp['round_name'] ?? 'Ingen') . "</td>";
            echo "<td>" . ($comp['organizer_name'] ?? 'Ingen') . "</td>";
            echo "<td>" . htmlspecialchars($comp['location']) . "</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
    } else {
        echo "<p style='color: orange;'>⚠️ Ingen stevner funnet</p>\n";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil: " . $e->getMessage() . "</p>\n";
}

echo "<hr>\n";

// Test 6: Test OrganizerHelper::getOrganizersWithEvents()
echo "<h2>6. Test OrganizerHelper::getOrganizersWithEvents()</h2>\n";

try {
    $roundsData = \Jaktfeltcup\Helpers\OrganizerHelper::getOrganizersWithEvents();
    
    echo "<p><strong>Antall runder returnert:</strong> " . count($roundsData) . "</p>\n";
    
    if (!empty($roundsData)) {
        echo "<pre>" . print_r($roundsData, true) . "</pre>\n";
    } else {
        echo "<p style='color: orange;'>⚠️ Ingen data returnert fra getOrganizersWithEvents()</p>\n";
        
        // Debug: Check what's happening
        echo "<h3>Debug: Sjekk aktiv sesong</h3>\n";
        $activeSeason = $database->queryOne(
            "SELECT id, name, year, is_active FROM jaktfelt_seasons WHERE is_active = 1 ORDER BY year DESC LIMIT 1"
        );
        
        if ($activeSeason) {
            echo "<p style='color: green;'>✅ Aktiv sesong funnet: " . htmlspecialchars($activeSeason['name']) . " (ID: " . $activeSeason['id'] . ")</p>\n";
            
            // Check rounds for this season
            $seasonRounds = $database->queryAll(
                "SELECT * FROM jaktfelt_rounds WHERE season_id = ? AND is_active = 1",
                [$activeSeason['id']]
            );
            
            echo "<p><strong>Runder for denne sesongen:</strong> " . count($seasonRounds) . "</p>\n";
            
            if (!empty($seasonRounds)) {
                echo "<pre>" . print_r($seasonRounds, true) . "</pre>\n";
            }
        } else {
            echo "<p style='color: red;'>❌ Ingen aktiv sesong funnet</p>\n";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil: " . $e->getMessage() . "</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>\n";
}

echo "<hr>\n";

// Test 7: Test OrganizerHelper::getAllOrganizers()
echo "<h2>7. Test OrganizerHelper::getAllOrganizers()</h2>\n";

try {
    $organizers = \Jaktfeltcup\Helpers\OrganizerHelper::getAllOrganizers();
    
    echo "<p><strong>Antall arrangører returnert:</strong> " . count($organizers) . "</p>\n";
    
    if (!empty($organizers)) {
        echo "<pre>" . print_r($organizers, true) . "</pre>\n";
    } else {
        echo "<p style='color: orange;'>⚠️ Ingen arrangører returnert fra getAllOrganizers()</p>\n";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Feil: " . $e->getMessage() . "</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>\n";
}

echo "<hr>\n";
echo "<p><strong>Debug fullført:</strong> " . date('Y-m-d H:i:s') . "</p>\n";
echo "<p><a href='" . base_url('arrangor') . "' class='btn btn-primary'>Gå til arrangør-siden</a></p>\n";
?>

