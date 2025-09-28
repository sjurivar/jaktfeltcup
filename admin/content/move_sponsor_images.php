<?php
/**
 * Move Sponsor Images
 * Move sponsor images to a different directory to avoid ad-blocker issues
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "<h1>Move Sponsor Images</h1>";

try {
    // Create new directory for sponsor images
    $newDir = __DIR__ . '/../../assets/images/sponsors/';
    if (!is_dir($newDir)) {
        mkdir($newDir, 0755, true);
        echo "<p>✅ Created new directory: assets/images/sponsors/</p>";
    }
    
    // Get all sponsors
    $sponsors = $database->queryAll("SELECT id, name, logo_filename FROM jaktfelt_sponsors WHERE is_active = 1");
    
    echo "<h2>Processing " . count($sponsors) . " sponsors</h2>";
    
    foreach ($sponsors as $sponsor) {
        echo "<h3>Processing: " . htmlspecialchars($sponsor['name']) . "</h3>";
        
        if (!empty($sponsor['logo_filename'])) {
            $oldPath = __DIR__ . '/../../src/Bilder/sponsorer/' . $sponsor['logo_filename'];
            $newFilename = 'sponsor_' . $sponsor['id'] . '.png';
            $newPath = $newDir . $newFilename;
            
            echo "<p>Old path: " . htmlspecialchars($oldPath) . "</p>";
            echo "<p>New path: " . htmlspecialchars($newPath) . "</p>";
            
            if (file_exists($oldPath)) {
                if (copy($oldPath, $newPath)) {
                    // Update database with new path
                    $newUrl = '/jaktfeltcup/assets/images/sponsors/' . $newFilename;
                    $database->execute(
                        "UPDATE jaktfelt_sponsors SET logo_filename = ?, logo_url = ? WHERE id = ?",
                        [$newFilename, $newUrl, $sponsor['id']]
                    );
                    echo "<p>✅ Successfully moved and updated database</p>";
                    echo "<p>New URL: " . htmlspecialchars($newUrl) . "</p>";
                } else {
                    echo "<p>❌ Failed to copy file</p>";
                }
            } else {
                echo "<p>❌ Old file does not exist</p>";
            }
        } else {
            echo "<p>⚠️ No logo filename in database</p>";
        }
    }
    
    echo "<h2>Summary</h2>";
    echo "<p>Sponsor images have been moved to assets/images/sponsors/ to avoid ad-blocker issues.</p>";
    echo "<p><a href='index.php'>Go to Content Management</a></p>";
    echo "<p><a href='../../sponsor/presentasjon'>Test Sponsor Presentation</a></p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>
