<?php
/**
 * Create Working Users Script
 * 
 * This script creates users with correct password hashes
 * that will work with the login system.
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "👥 Create Working Users Script\n";
echo "==============================\n\n";

// Users to create
$users = [
    [
        'username' => 'admin',
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@jaktfeltcup.no',
        'password' => 'admin123',
        'role' => 'admin'
    ],
    [
        'username' => 'db.manager',
        'first_name' => 'Database',
        'last_name' => 'Manager',
        'email' => 'db.manager@jaktfeltcup.no',
        'password' => 'db123',
        'role' => 'databasemanager'
    ],
    [
        'username' => 'content.manager',
        'first_name' => 'Content',
        'last_name' => 'Manager',
        'email' => 'content.manager@jaktfeltcup.no',
        'password' => 'content123',
        'role' => 'contentmanager'
    ],
    [
        'username' => 'role.manager',
        'first_name' => 'Role',
        'last_name' => 'Manager',
        'email' => 'role.manager@jaktfeltcup.no',
        'password' => 'role123',
        'role' => 'rolemanager'
    ],
    [
        'username' => 'sjur.ivar',
        'first_name' => 'Sjur Ivar',
        'last_name' => 'Hjellum',
        'email' => 'sjur.ivar@hjellum.net',
        'password' => 'VolvoV90',
        'role' => 'user'
    ],
    [
        'username' => 'ab_sollie',
        'first_name' => 'Anne Britt',
        'last_name' => 'Sollie Fladvad',
        'email' => 'ab_sollie@hotmail.com',
        'password' => 'AnneB',
        'role' => 'user'
    ]

];

try {
    echo "🔍 Checking if roles exist...\n";
    
    // Check if roles exist
    $roles = $database->queryAll("SELECT id, role_name FROM jaktfelt_roles");
    if (empty($roles)) {
        echo "❌ No roles found. Please run the roles setup script first.\n";
        echo "Run: php scripts/setup/setup_roles.php\n";
        exit(1);
    }
    
    echo "✅ Found " . count($roles) . " roles in database\n\n";
    
    echo "👤 Creating users:\n";
    
    foreach ($users as $userData) {
        // Check if user already exists
        $existingUser = $database->queryOne(
            "SELECT id FROM jaktfelt_users WHERE username = ? OR email = ?",
            [$userData['username'], $userData['email']]
        );
        
        if ($existingUser) {
            echo "⚠️  User {$userData['username']} already exists, skipping...\n";
            continue;
        }
        
        // Generate password hash
        $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Create user
        $userId = $database->query(
            "INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at, is_test_data) VALUES (?, ?, ?, ?, ?, 1, NOW(), 1)",
            [
                $userData['username'],
                $userData['first_name'],
                $userData['last_name'],
                $userData['email'],
                $passwordHash
            ]
        );
        
        if ($userId) {
            echo "✅ Created user: {$userData['username']}\n";
            
            // Assign role
            $role = $database->queryOne(
                "SELECT id FROM jaktfelt_roles WHERE role_name = ?",
                [$userData['role']]
            );
            
            if ($role) {
                $database->query(
                    "INSERT INTO jaktfelt_user_roles (user_id, role_id, is_test_data) VALUES (?, ?, 1)",
                    [$userId, $role['id']]
                );
                echo "   Assigned role: {$userData['role']}\n";
            } else {
                echo "   ⚠️  Role '{$userData['role']}' not found\n";
            }
        } else {
            echo "❌ Failed to create user: {$userData['username']}\n";
        }
    }
    
    echo "\n🧪 Testing created users:\n";
    
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
    
    echo "\n✅ User creation completed successfully!\n";
    echo "You can now log in with the credentials above.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
