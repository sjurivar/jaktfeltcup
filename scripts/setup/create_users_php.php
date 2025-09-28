<?php
/**
 * PHP User Creation Script
 * Creates users with hashed passwords and assigns roles
 * 
 * Usage: php create_users_php.php
 */

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// User data array - modify these values as needed
$users = [
    [
        'username' => 'admin',
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@jaktfeltcup.no',
        'password' => 'password123',
        'role' => 'admin'
    ],
    [
        'username' => 'db.manager',
        'first_name' => 'Database',
        'last_name' => 'Manager',
        'email' => 'db.manager@jaktfeltcup.no',
        'password' => 'password123',
        'role' => 'databasemanager'
    ],
    [
        'username' => 'content.manager',
        'first_name' => 'Content',
        'last_name' => 'Manager',
        'email' => 'content.manager@jaktfeltcup.no',
        'password' => 'password123',
        'role' => 'contentmanager'
    ],
    [
        'username' => 'role.manager',
        'first_name' => 'Role',
        'last_name' => 'Manager',
        'email' => 'role.manager@jaktfeltcup.no',
        'password' => 'password123',
        'role' => 'rolemanager'
    ],
    [
        'username' => 'user1',
        'first_name' => 'Regular',
        'last_name' => 'User',
        'email' => 'user1@jaktfeltcup.no',
        'password' => 'password123',
        'role' => 'user'
    ]
];

echo "🚀 Creating users with roles...\n\n";

try {
    // Create users
    foreach ($users as $userData) {
        echo "Creating user: {$userData['username']}... ";
        
        // Hash password
        $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Check if user already exists
        $existingUser = $database->queryOne(
            "SELECT id FROM jaktfelt_users WHERE username = ? OR email = ?",
            [$userData['username'], $userData['email']]
        );
        
        if ($existingUser) {
            echo "❌ User already exists\n";
            continue;
        }
        
        // Insert user
        $userId = $database->execute(
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
            echo "✅ User created (ID: $userId)\n";
            
            // Get role ID
            $role = $database->queryOne(
                "SELECT id FROM jaktfelt_roles WHERE role_name = ?",
                [$userData['role']]
            );
            
            if ($role) {
                // Assign role to user
                $database->execute(
                    "INSERT INTO jaktfelt_user_roles (user_id, role_id, is_test_data) VALUES (?, ?, 1)",
                    [$userId, $role['id']]
                );
                echo "   ✅ Role '{$userData['role']}' assigned\n";
            } else {
                echo "   ❌ Role '{$userData['role']}' not found\n";
            }
        } else {
            echo "❌ Failed to create user\n";
        }
    }
    
    echo "\n🎉 User creation completed!\n\n";
    
    // Display created users
    echo "📋 Created users:\n";
    echo str_repeat("-", 80) . "\n";
    printf("%-20s %-15s %-25s %-15s %s\n", "Username", "Name", "Email", "Role", "Password");
    echo str_repeat("-", 80) . "\n";
    
    $createdUsers = $database->queryAll(
        "SELECT u.username, u.first_name, u.last_name, u.email, r.role_name 
         FROM jaktfelt_users u
         JOIN jaktfelt_user_roles ur ON u.id = ur.user_id
         JOIN jaktfelt_roles r ON ur.role_id = r.id
         WHERE u.username IN (" . implode(',', array_fill(0, count($users), '?')) . ")
         ORDER BY u.username",
        array_column($users, 'username')
    );
    
    foreach ($createdUsers as $user) {
        printf("%-20s %-15s %-25s %-15s %s\n", 
            $user['username'], 
            $user['first_name'] . ' ' . $user['last_name'],
            $user['email'],
            $user['role_name'],
            'password123'
        );
    }
    
    echo str_repeat("-", 80) . "\n";
    echo "\n💡 All users have password: password123\n";
    echo "🔗 Login URLs:\n";
    echo "   Admin: http://localhost/jaktfeltcup/admin\n";
    echo "   Content: http://localhost/jaktfeltcup/arrangor (login as content.manager)\n";
    echo "   Database: http://localhost/jaktfeltcup/admin/database (login as db.manager)\n";
    echo "   Roles: http://localhost/jaktfeltcup/admin/roles (login as role.manager)\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
