<?php
// Direct test of sponsor image
$filename = 'Jarnheimr_1759043029.png';
$filepath = __DIR__ . '/src/Bilder/sponsorer/' . $filename;

echo "<h1>Direct Image Test</h1>";
echo "<p>Testing file: $filename</p>";
echo "<p>Full path: $filepath</p>";
echo "<p>File exists: " . (file_exists($filepath) ? 'YES' : 'NO') . "</p>";

if (file_exists($filepath)) {
    echo "<h2>Image Test</h2>";
    echo "<p>Direct URL: <a href='/jaktfeltcup/src/Bilder/sponsorer/$filename' target='_blank'>/jaktfeltcup/src/Bilder/sponsorer/$filename</a></p>";
    echo "<p>Image:</p>";
    echo "<img src='/jaktfeltcup/src/Bilder/sponsorer/$filename' alt='Test' style='max-width: 200px; border: 1px solid #ccc;'>";
    
    echo "<h2>Alternative URLs to test:</h2>";
    echo "<p><a href='http://localhost/jaktfeltcup/src/Bilder/sponsorer/$filename' target='_blank'>http://localhost/jaktfeltcup/src/Bilder/sponsorer/$filename</a></p>";
    echo "<p><a href='http://localhost/jaktfeltcup/src/Bilder/sponsorer/$filename' target='_blank'>http://localhost/jaktfeltcup/src/Bilder/sponsorer/$filename</a></p>";
} else {
    echo "<h2>File not found!</h2>";
    echo "<p>Let's check what files exist:</p>";
    
    $sponsorDir = __DIR__ . '/src/Bilder/sponsorer/';
    if (is_dir($sponsorDir)) {
        $files = scandir($sponsorDir);
        echo "<ul>";
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "<li>$file</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>Sponsor directory does not exist!</p>";
    }
}
?>
