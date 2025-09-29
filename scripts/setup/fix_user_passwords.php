<?php
/**
 * Fix User Passwords Script
 * 
 * This script generates correct PHP password_hash() hashes for users
 * and updates the database with working passwords.
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "🔧 Fix User Passwords Script\n";
echo "============================\n\n";

// Test users with their desired passwords
$users = [
    [
        'username' => 'admin',
        'email' => 'admin@jaktfeltcup.no',
        'password' => 'admin123',
        'role' => 'admin'
    ],
    [
        'username' => 'db.manager',
        'email' => 'db.manager@jaktfeltcup.no',
        'password' => 'db123',
        'role' => 'databasemanager'
    ],
    [
        'username' => 'content.manager',
        'email' => 'content.manager@jaktfeltcup.no',
        'password' => 'content123',
        'role' => 'contentmanager'
    ],
    [
        'username' => 'role.manager',
        'email' => 'role.manager@jaktfeltcup.no',
        'password' => 'role123',
        'role' => 'rolemanager'
    ],
    [
        'username' => 'sjur.ivar',
        'email' => 'sjur.ivar@hjellum.net',
        'password' => 'VolvoV90',
        'role' => 'user'
    ]
];

try {
    echo "📊 Current users in database:\n";
    $existingUsers = $database->queryAll("SELECT id, username, email, password_hash FROM jaktfelt_users ORDER BY username");
    
    if (empty($existingUsers)) {
        echo "❌ No users found in database. Please run the user creation script first.\n";
        exit(1);
    }
    
    foreach ($existingUsers as $user) {
        echo "  - {$user['username']} ({$user['email']})\n";
    }
    echo "\n";
    
    echo "🔐 Updating passwords for users:\n";
    
    foreach ($users as $userData) {
        // Generate new password hash
        $newHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Update user password
        $result = $database->query(
            "UPDATE jaktfelt_users SET password_hash = ? WHERE username = ?",
            [$newHash, $userData['username']]
        );
        
        if ($result) {
            echo "✅ Updated password for {$userData['username']} ({$userData['email']})\n";
            echo "   Password: {$userData['password']}\n";
            echo "   Hash: " . substr($newHash, 0, 20) . "...\n\n";
        } else {
            echo "❌ Failed to update password for {$userData['username']}\n\n";
        }
    }
    
    echo "🧪 Testing password verification:\n";
    
    // Test password verification
    foreach ($users as $userData) {
        $user = $database->queryOne(
            "SELECT password_hash FROM jaktfelt_users WHERE username = ?",
            [$userData['username']]
        );
        
        if ($user && password_verify($userData['password'], $user['password_hash'])) {
            echo "✅ Password verification successful for {$userData['username']}\n";
        } else {
            echo "❌ Password verification failed for {$userData['username']}\n";
        }
    }
    
    echo "\n🎯 Login Information:\n";
    echo "===================\n";
    foreach ($users as $userData) {
        echo "Username: {$userData['username']}\n";
        echo "Email: {$userData['email']}\n";
        echo "Password: {$userData['password']}\n";
        echo "Role: {$userData['role']}\n";
        echo "---\n";
    }
    
    echo "\n✅ Password update completed successfully!\n";
    echo "You can now log in with the credentials above.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
