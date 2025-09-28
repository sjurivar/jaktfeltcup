<?php
// Simple test to check if sponsor file exists
$filename = 'Jarnheimr_1759043029.png';
$filepath = __DIR__ . '/src/Bilder/sponsorer/' . $filename;

echo "Testing sponsor file...\n";
echo "Filename: $filename\n";
echo "Full path: $filepath\n";
echo "File exists: " . (file_exists($filepath) ? 'YES' : 'NO') . "\n";

if (file_exists($filepath)) {
    echo "File size: " . filesize($filepath) . " bytes\n";
    echo "File permissions: " . substr(sprintf('%o', fileperms($filepath)), -4) . "\n";
} else {
    echo "File does not exist!\n";
    
    // List all files in the directory
    $sponsorDir = __DIR__ . '/src/Bilder/sponsorer/';
    echo "Directory exists: " . (is_dir($sponsorDir) ? 'YES' : 'NO') . "\n";
    
    if (is_dir($sponsorDir)) {
        $files = scandir($sponsorDir);
        echo "Files in directory:\n";
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "  - $file\n";
            }
        }
    }
}
?>
