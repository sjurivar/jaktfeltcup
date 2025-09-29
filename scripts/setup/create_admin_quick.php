<?php
/**
 * Quick Admin Creation Script
 * 
 * This script creates a single admin user quickly
 * for testing and development purposes.
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "👑 Quick Admin Creation Script\n";
echo "==============================\n\n";

// Admin user details
$adminData = [
    'username' => 'admin',
    'first_name' => 'Admin',
    'last_name' => 'User',
    'email' => 'admin@jaktfeltcup.no',
    'password' => 'admin123'
];

try {
    echo "🔍 Checking if admin user already exists...\n";
    
    $existingAdmin = $database->queryOne(
        "SELECT id FROM jaktfelt_users WHERE username = ? OR email = ?",
        [$adminData['username'], $adminData['email']]
    );
    
    if ($existingAdmin) {
        echo "⚠️  Admin user already exists with ID: {$existingAdmin['id']}\n";
        echo "Updating password instead...\n";
        
        // Update password
        $newHash = password_hash($adminData['password'], PASSWORD_DEFAULT);
        $result = $database->query(
            "UPDATE jaktfelt_users SET password_hash = ? WHERE id = ?",
            [$newHash, $existingAdmin['id']]
        );
        
        if ($result) {
            echo "✅ Updated admin password successfully!\n";
        } else {
            echo "❌ Failed to update admin password\n";
            exit(1);
        }
        
        $adminId = $existingAdmin['id'];
    } else {
        echo "👤 Creating new admin user...\n";
        
        // Generate password hash
        $passwordHash = password_hash($adminData['password'], PASSWORD_DEFAULT);
        
        // Create admin user
        $adminId = $database->query(
            "INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at, is_test_data) VALUES (?, ?, ?, ?, ?, 1, NOW(), 1)",
            [
                $adminData['username'],
                $adminData['first_name'],
                $adminData['last_name'],
                $adminData['email'],
                $passwordHash
            ]
        );
        
        if ($adminId) {
            echo "✅ Created admin user successfully!\n";
        } else {
            echo "❌ Failed to create admin user\n";
            exit(1);
        }
    }
    
    echo "\n🔐 Checking roles...\n";
    
    // Check if admin role exists
    $adminRole = $database->queryOne(
        "SELECT id FROM jaktfelt_roles WHERE role_name = 'admin'"
    );
    
    if (!$adminRole) {
        echo "⚠️  Admin role not found. Creating it...\n";
        
        $database->query(
            "INSERT INTO jaktfelt_roles (role_name, description, is_test_data) VALUES ('admin', 'Administrator with full access', 1)"
        );
        
        $adminRole = $database->queryOne(
            "SELECT id FROM jaktfelt_roles WHERE role_name = 'admin'"
        );
    }
    
    if ($adminRole) {
        echo "✅ Admin role found (ID: {$adminRole['id']})\n";
        
        // Check if user already has admin role
        $existingRole = $database->queryOne(
            "SELECT id FROM jaktfelt_user_roles WHERE user_id = ? AND role_id = ?",
            [$adminId, $adminRole['id']]
        );
        
        if (!$existingRole) {
            echo "🔗 Assigning admin role to user...\n";
            
            $database->query(
                "INSERT INTO jaktfelt_user_roles (user_id, role_id, is_test_data) VALUES (?, ?, 1)",
                [$adminId, $adminRole['id']]
            );
            
            echo "✅ Admin role assigned successfully!\n";
        } else {
            echo "✅ Admin role already assigned\n";
        }
    } else {
        echo "❌ Failed to create/find admin role\n";
        exit(1);
    }
    
    echo "\n🧪 Testing admin login...\n";
    
    // Test password verification
    $user = $database->queryOne(
        "SELECT password_hash FROM jaktfelt_users WHERE id = ?",
        [$adminId]
    );
    
    if ($user && password_verify($adminData['password'], $user['password_hash'])) {
        echo "✅ Password verification: SUCCESS\n";
    } else {
        echo "❌ Password verification: FAILED\n";
    }
    
    echo "\n🎯 Admin Login Information:\n";
    echo "==========================\n";
    echo "Username: {$adminData['username']}\n";
    echo "Email: {$adminData['email']}\n";
    echo "Password: {$adminData['password']}\n";
    echo "Role: admin\n";
    echo "\nYou can now log in at: " . base_url('login') . "\n";
    echo "Admin panel: " . base_url('admin') . "\n";
    
    echo "\n✅ Admin user setup completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
