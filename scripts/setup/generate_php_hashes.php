<?php
/**
 * PHP Password Hash Generator for SQL Scripts
 * Generates bcrypt hashes compatible with PHP password_verify()
 * 
 * Usage: php generate_php_hashes.php
 */

echo "🔐 PHP Password Hash Generator for SQL\n";
echo str_repeat("=", 50) . "\n\n";

// Common passwords to generate hashes for
$passwords = [
    'password123',
    'admin123',
    'user123',
    'test123',
    'mypassword'
];

echo "Generated PHP password_hash() compatible hashes:\n";
echo str_repeat("-", 50) . "\n";

foreach ($passwords as $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "Password: $password\n";
    echo "Hash: $hash\n";
    echo str_repeat("-", 50) . "\n";
}

echo "\n💡 SQL Usage Examples:\n";
echo str_repeat("-", 50) . "\n";

$examplePassword = 'password123';
$exampleHash = password_hash($examplePassword, PASSWORD_DEFAULT);

echo "-- Insert user with PHP-compatible hash\n";
echo "INSERT INTO jaktfelt_users (username, password_hash, ...) VALUES\n";
echo "('admin', '$exampleHash', ...);\n\n";

echo "-- Verify hash works with PHP password_verify()\n";
echo "// In PHP code:\n";
echo "if (password_verify('$examplePassword', '\$hash_from_database')) {\n";
echo "    echo 'Login successful!';\n";
echo "}\n\n";

echo "🔍 Hash Information:\n";
echo str_repeat("-", 50) . "\n";
echo "Algorithm: " . password_get_info($exampleHash)['algoName'] . "\n";
echo "Options: " . json_encode(password_get_info($exampleHash)['options']) . "\n";
echo "Hash length: " . strlen($exampleHash) . " characters\n";

echo "\n✅ All hashes are compatible with PHP password_verify()\n";
?>
