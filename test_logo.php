<?php
/**
 * Test logo path
 */

// Include required files
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Helpers/ImageHelper.php';

use Jaktfeltcup\Helpers\ImageHelper;

echo "<h1>Logo Test</h1>\n";
echo "<hr>\n";

// Test 1: Check if logo file exists
$logoPath = __DIR__ . '/bilder/logoer/jaktfeltcup_logo.png';
echo "<h2>1. Sjekk om logo-fil eksisterer</h2>\n";
echo "<p><strong>Sti:</strong> " . htmlspecialchars($logoPath) . "</p>\n";

if (file_exists($logoPath)) {
    echo "<p style='color: green;'>✅ Logo-fil eksisterer</p>\n";
    echo "<p><strong>Størrelse:</strong> " . filesize($logoPath) . " bytes</p>\n";
} else {
    echo "<p style='color: red;'>❌ Logo-fil eksisterer ikke</p>\n";
}

echo "<hr>\n";

// Test 2: Test ImageHelper::getLogoUrl()
echo "<h2>2. Test ImageHelper::getLogoUrl()</h2>\n";
$logoUrl = ImageHelper::getLogoUrl();
echo "<p><strong>Logo URL:</strong> " . htmlspecialchars($logoUrl) . "</p>\n";

if ($logoUrl) {
    echo "<p style='color: green;'>✅ Logo URL generert</p>\n";
} else {
    echo "<p style='color: red;'>❌ Kunne ikke generere logo URL</p>\n";
}

echo "<hr>\n";

// Test 3: Test direct URL
echo "<h2>3. Test direkte URL</h2>\n";
$directUrl = base_url('bilder/logoer/jaktfeltcup_logo.png');
echo "<p><strong>Direkte URL:</strong> " . htmlspecialchars($directUrl) . "</p>\n";

echo "<hr>\n";

// Test 4: Show logo image
echo "<h2>4. Vis logo</h2>\n";
if ($logoUrl) {
    echo "<img src='" . htmlspecialchars($logoUrl) . "' alt='Jaktfeltcup Logo' style='max-width: 300px; border: 1px solid #ccc;'>\n";
} else {
    echo "<p style='color: red;'>❌ Kunne ikke vise logo</p>\n";
}

echo "<hr>\n";
echo "<p><strong>Test fullført:</strong> " . date('Y-m-d H:i:s') . "</p>\n";
?>
