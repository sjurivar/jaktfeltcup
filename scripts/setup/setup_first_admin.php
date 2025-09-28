<?php
/**
 * Setup First Admin User
 * Creates the first admin user when no users exist
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

echo "🔧 Setting up First Admin User...\n\n";

try {
    // Initialize database connection
    global $db_config;
    $database = new \Jaktfeltcup\Core\Database($db_config);
    
    echo "✅ Connected to database\n";
    
    // Check if any users exist
    $user_count = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_users")['count'];
    
    if ($user_count > 0) {
        echo "ℹ️  Users already exist in database ($user_count users found)\n";
        
        // Check if any admin users exist
        $admin_count = $database->queryOne(
            "SELECT COUNT(*) as count FROM jaktfelt_user_roles ur 
             JOIN jaktfelt_roles r ON ur.role_id = r.id 
             WHERE r.role_name = 'admin'"
        )['count'];
        
        if ($admin_count > 0) {
            echo "✅ Admin users already exist ($admin_count admin users found)\n";
            echo "🎉 No setup needed - you can log in with existing admin account!\n";
            exit(0);
        } else {
            echo "⚠️  No admin users found. You may need to assign admin role to existing users.\n";
            echo "💡 Go to /admin/roles to assign admin role to existing users.\n";
            exit(0);
        }
    }
    
    echo "📝 No users found - creating first admin user...\n";
    
    // Get admin details
    echo "\n🔐 Please provide admin user details:\n";
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
    
    // Ensure roles table exists and has admin role
    try {
        $admin_role = $database->queryOne("SELECT id FROM jaktfelt_roles WHERE role_name = 'admin'");
        if (!$admin_role) {
            echo "⚠️  Admin role not found - creating roles table...\n";
            
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
    
    echo "\n🎉 First admin user setup complete!\n";
    echo "📋 Login details:\n";
    echo "   Email: $email\n";
    echo "   Password: [hidden]\n";
    echo "\n🌐 You can now log in at: " . base_url('login') . "\n";
    echo "🔧 Admin dashboard: " . base_url('admin') . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
