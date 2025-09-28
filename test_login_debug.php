<?php
/**
 * Login Debug Script
 * Tests user login and database queries
 */

// Include required files
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Auth/AuthService.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);
$authService = new \Jaktfeltcup\Auth\AuthService($database);

echo "🔍 Login Debug Test\n";
echo str_repeat("=", 50) . "\n\n";

try {
    // Test 1: Check if users table exists and has data
    echo "1. Checking users table...\n";
    $users = $database->queryAll("SELECT id, username, email, is_active FROM jaktfelt_users LIMIT 5");
    
    if (empty($users)) {
        echo "❌ No users found in jaktfelt_users table\n";
        echo "💡 Run the user creation scripts first:\n";
        echo "   php scripts/setup/create_users_php.php\n";
        echo "   or\n";
        echo "   Run database/test_users.sql in MySQL\n";
        exit(1);
    }
    
    echo "✅ Found " . count($users) . " users:\n";
    foreach ($users as $user) {
        echo "   - {$user['username']} ({$user['email']}) - Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
    }
    
    // Test 2: Try to find a specific user
    echo "\n2. Testing user lookup...\n";
    $testEmail = 'content.manager@jaktfeltcup.no';
    $user = $database->queryOne(
        "SELECT * FROM jaktfelt_users WHERE email = ? AND is_active = 1",
        [$testEmail]
    );
    
    if (!$user) {
        echo "❌ User not found: $testEmail\n";
        echo "💡 Make sure user exists and is_active = 1\n";
    } else {
        echo "✅ User found: {$user['username']} ({$user['email']})\n";
        echo "   Password hash: " . substr($user['password_hash'], 0, 20) . "...\n";
        echo "   Hash length: " . strlen($user['password_hash']) . " characters\n";
        
        // Test 3: Test password verification
        echo "\n3. Testing password verification...\n";
        $testPassword = 'password123';
        $isValid = password_verify($testPassword, $user['password_hash']);
        
        if ($isValid) {
            echo "✅ Password verification: SUCCESS\n";
        } else {
            echo "❌ Password verification: FAILED\n";
            echo "💡 Password hash might be incompatible\n";
        }
    }
    
    // Test 4: Try actual login
    echo "\n4. Testing actual login...\n";
    try {
        $loginResult = $authService->login($testEmail, 'password123');
        echo "✅ Login successful!\n";
        echo "   User ID: " . $loginResult['user_id'] . "\n";
        echo "   Username: " . $loginResult['username'] . "\n";
    } catch (Exception $e) {
        echo "❌ Login failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "🔗 Test complete!\n";
?>
