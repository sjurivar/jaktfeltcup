<?php
/**
 * Emergency Admin Setup
 * Creates admin user without role checks (for initial setup)
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

echo "🚨 Emergency Admin Setup\n";
echo "This script creates an admin user without role checks.\n";
echo "Use this only for initial setup when no users exist.\n\n";

try {
    // Initialize database connection
    global $db_config;
    $database = new \Jaktfeltcup\Core\Database($db_config);
    
    echo "✅ Connected to database\n";
    
    // Get admin details
    echo "Enter admin user details:\n";
    echo "First Name: ";
    $first_name = trim(fgets(STDIN));
    echo "Last Name: ";
    $last_name = trim(fgets(STDIN));
    echo "Email: ";
    $email = trim(fgets(STDIN));
    echo "Password: ";
    $password = trim(fgets(STDIN));
    
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        throw new Exception("All fields are required!");
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format!");
    }
    
    // Check if email already exists
    $existing_user = $database->queryOne("SELECT id FROM jaktfelt_users WHERE email = ?", [$email]);
    if ($existing_user) {
        throw new Exception("User with this email already exists!");
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Create user
    $username = strtolower($first_name . '.' . $last_name);
    $user_id = $database->execute(
        "INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at) 
         VALUES (?, ?, ?, ?, ?, 1, NOW())",
        [$username, $first_name, $last_name, $email, $hashed_password]
    );
    
    echo "✅ User created successfully (ID: $user_id)\n";
    
    // Try to set up roles and assign admin role
    try {
        // Check if roles table exists
        $roles_exist = $database->queryOne("SHOW TABLES LIKE 'jaktfelt_roles'");
        
        if (!$roles_exist) {
            echo "⚠️  Roles table doesn't exist - creating it...\n";
            
            // Create roles table
            $roles_sql = file_get_contents(__DIR__ . '/../../database/roles_tables.sql');
            $statements = array_filter(array_map('trim', explode(';', $roles_sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement) && !preg_match('/^--/', $statement)) {
                    try {
                        $database->execute($statement);
                    } catch (Exception $e) {
                        // Ignore "table already exists" errors
                        if (strpos($e->getMessage(), 'already exists') === false) {
                            throw $e;
                        }
                    }
                }
            }
            
            echo "✅ Roles table created\n";
        }
        
        // Get admin role ID
        $admin_role = $database->queryOne("SELECT id FROM jaktfelt_roles WHERE role_name = 'admin'");
        
        if ($admin_role) {
            // Assign admin role to user
            $database->execute(
                "INSERT INTO jaktfelt_user_roles (user_id, role_id) VALUES (?, ?)",
                [$user_id, $admin_role['id']]
            );
            
            echo "✅ Admin role assigned to user\n";
        } else {
            echo "⚠️  Could not find admin role - you may need to set up roles manually\n";
        }
        
    } catch (Exception $e) {
        echo "⚠️  Could not set up roles: " . $e->getMessage() . "\n";
        echo "💡 You can set up roles later using: php scripts/setup/setup_roles.php\n";
    }
    
    echo "\n🎉 Emergency admin user setup complete!\n";
    echo "📋 Login details:\n";
    echo "   Email: $email\n";
    echo "   Password: [hidden]\n";
    echo "\n🌐 You can now log in at: " . base_url('login') . "\n";
    echo "🔧 Admin dashboard: " . base_url('admin') . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
