<?php
/**
 * Password Hash Generator
 * Generates password hashes for use in SQL scripts
 * 
 * Usage: php generate_password_hash.php
 */

echo "🔐 Password Hash Generator\n";
echo str_repeat("=", 50) . "\n\n";

// Get password from user input
echo "Enter password to hash: ";
$password = trim(fgets(STDIN));

if (empty($password)) {
    echo "❌ No password entered. Exiting.\n";
    exit(1);
}

// Generate hash
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "\n📋 Results:\n";
echo str_repeat("-", 50) . "\n";
echo "Password: $password\n";
echo "Hash: $hash\n";
echo str_repeat("-", 50) . "\n";

// Verify the hash
if (password_verify($password, $hash)) {
    echo "✅ Hash verification: PASSED\n";
} else {
    echo "❌ Hash verification: FAILED\n";
}

echo "\n💡 Copy the hash above to use in your SQL scripts.\n";
echo "Example SQL usage:\n";
echo "INSERT INTO jaktfelt_users (username, password_hash, ...) VALUES ('user1', '$hash', ...);\n";
?>
