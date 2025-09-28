<?php
/**
 * Quick Setup for Admin Access
 * Sets up roles and creates admin user quickly
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/src/Core/Database.php';

echo "🚀 Quick Admin Setup\n";
echo "==================\n\n";

try {
    // Initialize database connection
    global $db_config;
    $database = new \Jaktfeltcup\Core\Database($db_config);
    
    echo "✅ Connected to database\n";
    
    // Check if roles table exists
    $roles_exist = $database->queryOne("SHOW TABLES LIKE 'jaktfelt_roles'");
    
    if (!$roles_exist) {
        echo "📝 Creating roles table...\n";
        
        // Create roles table
        $roles_sql = file_get_contents(__DIR__ . '/database/roles_tables.sql');
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
    } else {
        echo "✅ Roles table already exists\n";
    }
    
    // Check if any users exist
    $user_count = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_users")['count'];
    
    if ($user_count == 0) {
        echo "📝 Creating first admin user...\n";
        
        // Create admin user
        $username = 'admin';
        $first_name = 'Admin';
        $last_name = 'User';
        $email = 'admin@jaktfeltcup.no';
        $password = 'admin123';
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $user_id = $database->execute(
            "INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at) 
             VALUES (?, ?, ?, ?, ?, 1, NOW())",
            [$username, $first_name, $last_name, $email, $hashed_password]
        );
        
        echo "✅ Admin user created (ID: $user_id)\n";
        echo "📧 Email: $email\n";
        echo "🔑 Password: $password\n";
        
        // Assign admin role
        $admin_role = $database->queryOne("SELECT id FROM jaktfelt_roles WHERE role_name = 'admin'");
        if ($admin_role) {
            $database->execute(
                "INSERT INTO jaktfelt_user_roles (user_id, role_id) VALUES (?, ?)",
                [$user_id, $admin_role['id']]
            );
            echo "✅ Admin role assigned\n";
        }
        
    } else {
        echo "ℹ️  Users already exist ($user_count users found)\n";
        
        // Check if any admin users exist
        $admin_count = $database->queryOne(
            "SELECT COUNT(*) as count FROM jaktfelt_user_roles ur 
             JOIN jaktfelt_roles r ON ur.role_id = r.id 
             WHERE r.role_name = 'admin'"
        )['count'];
        
        if ($admin_count == 0) {
            echo "⚠️  No admin users found. You need to assign admin role to existing users.\n";
            echo "💡 Go to /admin/roles to assign admin role.\n";
        } else {
            echo "✅ Admin users found ($admin_count admin users)\n";
        }
    }
    
    echo "\n🎉 Setup complete!\n";
    echo "🌐 You can now:\n";
    echo "   1. Go to: http://localhost/jaktfeltcup/login\n";
    echo "   2. Login with: admin@jaktfeltcup.no / admin123\n";
    echo "   3. Then go to: http://localhost/jaktfeltcup/admin\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
