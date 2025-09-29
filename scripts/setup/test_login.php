<?php
/**
 * Test Login Script
 * 
 * This script tests the login functionality with different users
 * to verify that password hashing is working correctly.
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';
require_once __DIR__ . '/../../src/Auth/AuthService.php';

// Initialize database and auth service
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);
$authService = new \Jaktfeltcup\Auth\AuthService($database);

echo "🔐 Test Login Script\n";
echo "====================\n\n";

// Test users
$testUsers = [
    ['username' => 'admin', 'password' => 'admin123'],
    ['username' => 'db.manager', 'password' => 'db123'],
    ['username' => 'content.manager', 'password' => 'content123'],
    ['username' => 'role.manager', 'password' => 'role123'],
    ['username' => 'user1', 'password' => 'user123']
];

try {
    echo "📊 Available users in database:\n";
    $users = $database->queryAll("SELECT username, email, password_hash FROM jaktfelt_users ORDER BY username");
    
    if (empty($users)) {
        echo "❌ No users found in database.\n";
        echo "Please run: php scripts/setup/create_working_users.php\n";
        exit(1);
    }
    
    foreach ($users as $user) {
        echo "  - {$user['username']} ({$user['email']})\n";
    }
    echo "\n";
    
    echo "🧪 Testing login functionality:\n";
    echo "==============================\n";
    
    foreach ($testUsers as $testUser) {
        echo "\n🔍 Testing: {$testUser['username']}\n";
        
        // Test password verification directly
        $user = $database->queryOne(
            "SELECT * FROM jaktfelt_users WHERE username = ?",
            [$testUser['username']]
        );
        
        if (!$user) {
            echo "❌ User not found: {$testUser['username']}\n";
            continue;
        }
        
        // Test password hash
        if (password_verify($testUser['password'], $user['password_hash'])) {
            echo "✅ Password verification: SUCCESS\n";
            
            // Test full login
            $loginResult = $authService->login($user['email'], $testUser['password']);
            
            if ($loginResult) {
                echo "✅ Full login: SUCCESS\n";
                echo "   User ID: {$loginResult['id']}\n";
                echo "   Name: {$loginResult['first_name']} {$loginResult['last_name']}\n";
                echo "   Email: {$loginResult['email']}\n";
                
                // Get user roles
                $roles = $database->queryAll(
                    "SELECT r.role_name FROM jaktfelt_roles r 
                     JOIN jaktfelt_user_roles ur ON r.id = ur.role_id 
                     WHERE ur.user_id = ?",
                    [$loginResult['id']]
                );
                
                if (!empty($roles)) {
                    echo "   Roles: " . implode(', ', array_column($roles, 'role_name')) . "\n";
                } else {
                    echo "   ⚠️  No roles assigned\n";
                }
            } else {
                echo "❌ Full login: FAILED\n";
            }
        } else {
            echo "❌ Password verification: FAILED\n";
            echo "   Expected password: {$testUser['password']}\n";
            echo "   Hash in database: " . substr($user['password_hash'], 0, 20) . "...\n";
        }
    }
    
    echo "\n🎯 Summary:\n";
    echo "===========\n";
    echo "If all tests show ✅ SUCCESS, your login system is working correctly.\n";
    echo "If any tests show ❌ FAILED, run the password fix script:\n";
    echo "php scripts/setup/fix_user_passwords.php\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
