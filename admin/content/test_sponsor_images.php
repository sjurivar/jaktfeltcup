<?php
/**
 * Test Sponsor Images
 * Debug sponsor image loading
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "<h1>Sponsor Images Test</h1>";

try {
    // Get sponsors from database
    $sponsors = $database->queryAll(
        "SELECT name, logo_url, logo_filename FROM jaktfelt_sponsors WHERE is_active = 1"
    );
    
    echo "<h2>Database Sponsors</h2>";
    echo "<p>Found " . count($sponsors) . " sponsors</p>";
    
    foreach ($sponsors as $sponsor) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
        echo "<h3>" . htmlspecialchars($sponsor['name']) . "</h3>";
        echo "<p><strong>Logo URL:</strong> " . htmlspecialchars($sponsor['logo_url']) . "</p>";
        echo "<p><strong>Logo Filename:</strong> " . htmlspecialchars($sponsor['logo_filename']) . "</p>";
        
        // Check if file exists
        $filePath = __DIR__ . '/../../src/Bilder/sponsorer/' . $sponsor['logo_filename'];
        echo "<p><strong>File exists:</strong> " . (file_exists($filePath) ? 'YES' : 'NO') . "</p>";
        echo "<p><strong>File path:</strong> " . htmlspecialchars($filePath) . "</p>";
        
        // Try to display image
        if (file_exists($filePath)) {
            echo "<p><strong>Image:</strong></p>";
            echo "<img src='" . htmlspecialchars($sponsor['logo_url']) . "' alt='" . htmlspecialchars($sponsor['name']) . "' style='max-width: 200px; border: 1px solid #ccc;'>";
        }
        
        echo "</div>";
    }
    
    // Test ImageHelper
    echo "<h2>ImageHelper Test</h2>";
    $sponsorImages = \Jaktfeltcup\Helpers\ImageHelper::getSponsorImages();
    echo "<p>ImageHelper found " . count($sponsorImages) . " sponsors</p>";
    
    foreach ($sponsorImages as $sponsor) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
        echo "<h3>" . htmlspecialchars($sponsor['name']) . "</h3>";
        echo "<p><strong>Logo URL:</strong> " . htmlspecialchars($sponsor['logo_url']) . "</p>";
        echo "<p><strong>Sponsor Level:</strong> " . htmlspecialchars($sponsor['sponsor_level']) . "</p>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
