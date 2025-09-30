<?php
/**
 * Move logo from bilder/logoer/ to assets/images/logoer/
 * This ensures the logo is deployed with the application
 */

echo "<h1>Flytt Logo til Assets</h1>\n";
echo "<hr>\n";

// Create assets/images/logoer directory
$assetsDir = __DIR__ . '/assets/images/logoer';
if (!is_dir($assetsDir)) {
    if (mkdir($assetsDir, 0755, true)) {
        echo "<p style='color: green;'>✅ Opprettet mappe: assets/images/logoer/</p>\n";
    } else {
        echo "<p style='color: red;'>❌ Kunne ikke opprette mappe: assets/images/logoer/</p>\n";
        exit;
    }
} else {
    echo "<p style='color: blue;'>ℹ️ Mappe eksisterer allerede: assets/images/logoer/</p>\n";
}

echo "<hr>\n";

// Copy logo files
$sourceDir = __DIR__ . '/bilder/logoer/';
$targetDir = __DIR__ . '/assets/images/logoer/';

$logoFiles = [
    'jaktfeltcup_logo.png',
    'jaktfeltcup_logo2000x2000.png'
];

echo "<h2>Kopierer logo-filer</h2>\n";

foreach ($logoFiles as $file) {
    $sourcePath = $sourceDir . $file;
    $targetPath = $targetDir . $file;
    
    echo "<p><strong>Kopierer:</strong> " . htmlspecialchars($file) . "</p>\n";
    
    if (file_exists($sourcePath)) {
        if (copy($sourcePath, $targetPath)) {
            echo "<p style='color: green;'>✅ Kopiert: " . htmlspecialchars($file) . "</p>\n";
        } else {
            echo "<p style='color: red;'>❌ Kunne ikke kopiere: " . htmlspecialchars($file) . "</p>\n";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ Kildefil ikke funnet: " . htmlspecialchars($file) . "</p>\n";
    }
}

echo "<hr>\n";

// Test the new path
echo "<h2>Test ny sti</h2>\n";

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Helpers/ImageHelper.php';

use Jaktfeltcup\Helpers\ImageHelper;

$logoUrl = ImageHelper::getLogoUrl();
echo "<p><strong>Logo URL:</strong> " . htmlspecialchars($logoUrl) . "</p>\n";

if ($logoUrl) {
    echo "<p style='color: green;'>✅ Logo URL generert</p>\n";
    echo "<h3>Logo bilde:</h3>\n";
    echo "<img src='" . htmlspecialchars($logoUrl) . "' alt='Jaktfeltcup Logo' style='max-width: 300px; border: 1px solid #ccc;'>\n";
} else {
    echo "<p style='color: red;'>❌ Kunne ikke generere logo URL</p>\n";
}

echo "<hr>\n";
echo "<p><strong>Flytting fullført:</strong> " . date('Y-m-d H:i:s') . "</p>\n";
?>
