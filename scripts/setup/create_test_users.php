<?php
/**
 * Create Test Users with Different Roles
 * Creates 3 test users, each with a different role for testing
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

echo "👥 Creating Test Users with Different Roles...\n\n";

try {
    // Initialize database connection
    global $db_config;
    $database = new \Jaktfeltcup\Core\Database($db_config);
    
    echo "✅ Connected to database\n";
    
    // Check if roles table exists
    $roles_exist = $database->queryOne("SHOW TABLES LIKE 'jaktfelt_roles'");
    if (!$roles_exist) {
        echo "❌ Roles table does not exist!\n";
        echo "💡 Run: http://localhost/jaktfeltcup/quick_setup.php first\n";
        exit(1);
    }
    
    echo "✅ Roles table exists\n";
    
    // Test users data
    $test_users = [
        [
            'username' => 'db.manager',
            'first_name' => 'Database',
            'last_name' => 'Manager',
            'email' => 'db.manager@jaktfeltcup.no',
            'role' => 'databasemanager'
        ],
        [
            'username' => 'content.manager',
            'first_name' => 'Content',
            'last_name' => 'Manager',
            'email' => 'content.manager@jaktfeltcup.no',
            'role' => 'contentmanager'
        ],
        [
            'username' => 'role.manager',
            'first_name' => 'Role',
            'last_name' => 'Manager',
            'email' => 'role.manager@jaktfeltcup.no',
            'role' => 'rolemanager'
        ]
    ];
    
    $password = 'password123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    echo "🔑 Password for all test users: $password\n";
    echo "🔐 Hashed password: $hashed_password\n\n";
    
    echo "📝 Creating test users...\n";
    
    foreach ($test_users as $user_data) {
        // Check if user already exists
        $existing_user = $database->queryOne("SELECT id FROM jaktfelt_users WHERE username = ?", [$user_data['username']]);
        
        if ($existing_user) {
            echo "ℹ️  User {$user_data['username']} already exists (ID: {$existing_user['id']})\n";
            $user_id = $existing_user['id'];
        } else {
            // Create user
            $user_id = $database->execute(
                "INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at, is_test_data) 
                 VALUES (?, ?, ?, ?, ?, 1, NOW(), 1)",
                [$user_data['username'], $user_data['first_name'], $user_data['last_name'], $user_data['email'], $hashed_password]
            );
            
            echo "✅ Created user: {$user_data['username']} (ID: $user_id)\n";
        }
        
        // Get role ID
        $role = $database->queryOne("SELECT id FROM jaktfelt_roles WHERE role_name = ?", [$user_data['role']]);
        
        if (!$role) {
            echo "❌ Role {$user_data['role']} does not exist!\n";
            continue;
        }
        
        // Check if user already has this role
        $existing_role = $database->queryOne(
            "SELECT id FROM jaktfelt_user_roles WHERE user_id = ? AND role_id = ?",
            [$user_id, $role['id']]
        );
        
        if ($existing_role) {
            echo "ℹ️  User {$user_data['username']} already has role {$user_data['role']}\n";
        } else {
            // Assign role
            $database->execute(
                "INSERT INTO jaktfelt_user_roles (user_id, role_id, is_test_data) VALUES (?, ?, 1)",
                [$user_id, $role['id']]
            );
            
            echo "✅ Assigned role {$user_data['role']} to {$user_data['username']}\n";
        }
    }
    
    echo "\n🎉 Test users created successfully!\n";
    echo "\n📋 Login Information:\n";
    echo "===================\n";
    echo "Database Manager:\n";
    echo "  Email: db.manager@jaktfeltcup.no\n";
    echo "  Password: $password\n";
    echo "  Access: Database Management only\n\n";
    
    echo "Content Manager:\n";
    echo "  Email: content.manager@jaktfeltcup.no\n";
    echo "  Password: $password\n";
    echo "  Access: Content Management only\n\n";
    
    echo "Role Manager:\n";
    echo "  Email: role.manager@jaktfeltcup.no\n";
    echo "  Password: $password\n";
    echo "  Access: User & Role Management only\n\n";
    
    echo "🌐 Test the roles:\n";
    echo "1. Go to: http://localhost/jaktfeltcup/login\n";
    echo "2. Login with each user\n";
    echo "3. Go to: http://localhost/jaktfeltcup/admin\n";
    echo "4. See which modules each user can access\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
