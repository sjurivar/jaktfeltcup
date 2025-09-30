<?php
/**
 * Test if bilder/ directory is tracked by Git
 */

echo "<h1>Git Status Test</h1>\n";
echo "<hr>\n";

// Check if bilder directory exists
$bilderDir = __DIR__ . '/bilder';
echo "<h2>1. Sjekk om bilder/ mappen eksisterer</h2>\n";
echo "<p><strong>Sti:</strong> " . htmlspecialchars($bilderDir) . "</p>\n";

if (is_dir($bilderDir)) {
    echo "<p style='color: green;'>✅ bilder/ mappen eksisterer</p>\n";
    
    // List contents
    $contents = scandir($bilderDir);
    echo "<h3>Innhold i bilder/:</h3>\n";
    echo "<ul>\n";
    foreach ($contents as $item) {
        if ($item !== '.' && $item !== '..') {
            echo "<li>" . htmlspecialchars($item) . "</li>\n";
        }
    }
    echo "</ul>\n";
} else {
    echo "<p style='color: red;'>❌ bilder/ mappen eksisterer ikke</p>\n";
}

echo "<hr>\n";

// Check logoer subdirectory
$logoerDir = __DIR__ . '/bilder/logoer';
echo "<h2>2. Sjekk om bilder/logoer/ mappen eksisterer</h2>\n";
echo "<p><strong>Sti:</strong> " . htmlspecialchars($logoerDir) . "</p>\n";

if (is_dir($logoerDir)) {
    echo "<p style='color: green;'>✅ bilder/logoer/ mappen eksisterer</p>\n";
    
    // List contents
    $contents = scandir($logoerDir);
    echo "<h3>Innhold i bilder/logoer/:</h3>\n";
    echo "<ul>\n";
    foreach ($contents as $item) {
        if ($item !== '.' && $item !== '..') {
            $filePath = $logoerDir . '/' . $item;
            $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
            echo "<li>" . htmlspecialchars($item) . " (" . $fileSize . " bytes)</li>\n";
        }
    }
    echo "</ul>\n";
} else {
    echo "<p style='color: red;'>❌ bilder/logoer/ mappen eksisterer ikke</p>\n";
}

echo "<hr>\n";

// Check if files are accessible via web
echo "<h2>3. Test web-tilgang</h2>\n";
$logoUrl = '/jaktfeltcup/bilder/logoer/jaktfeltcup_logo.png';
echo "<p><strong>Logo URL:</strong> " . htmlspecialchars($logoUrl) . "</p>\n";

echo "<h3>Logo bilde:</h3>\n";
echo "<img src='" . htmlspecialchars($logoUrl) . "' alt='Jaktfeltcup Logo' style='max-width: 300px; border: 1px solid #ccc;'>\n";

echo "<hr>\n";
echo "<p><strong>Test fullført:</strong> " . date('Y-m-d H:i:s') . "</p>\n";
?>
