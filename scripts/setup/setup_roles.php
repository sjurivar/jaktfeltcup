<?php
/**
 * Setup Roles Tables
 * Creates role management tables and assigns default roles
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

echo "🔧 Setting up Role Management System...\n\n";

try {
    // Initialize database connection
    global $db_config;
    $database = new \Jaktfeltcup\Core\Database($db_config);
    
    echo "✅ Connected to database\n";
    
    // Read and execute roles table SQL
    $sqlFile = __DIR__ . '/../../database/roles_tables.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    $executed = 0;
    foreach ($statements as $statement) {
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            try {
                $database->execute($statement);
                $executed++;
            } catch (Exception $e) {
                // Ignore "table already exists" errors
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "⚠️  Warning: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "✅ Role tables created/updated ($executed statements executed)\n";
    
    // Verify setup
    $roles = $database->queryAll("SELECT * FROM jaktfelt_roles ORDER BY role_name");
    echo "✅ Found " . count($roles) . " roles:\n";
    foreach ($roles as $role) {
        echo "   - " . $role['role_name'] . " (" . $role['description'] . ")\n";
    }
    
    // Check if first user has admin role
    $adminUsers = $database->queryAll(
        "SELECT u.first_name, u.last_name, u.email 
         FROM jaktfelt_users u 
         JOIN jaktfelt_user_roles ur ON u.id = ur.user_id 
         JOIN jaktfelt_roles r ON ur.role_id = r.id 
         WHERE r.role_name = 'admin'"
    );
    
    if (!empty($adminUsers)) {
        echo "✅ Admin users found:\n";
        foreach ($adminUsers as $user) {
            echo "   - " . $user['first_name'] . " " . $user['last_name'] . " (" . $user['email'] . ")\n";
        }
    } else {
        echo "⚠️  No admin users found. First user will be assigned admin role.\n";
    }
    
    echo "\n🎉 Role management system setup complete!\n";
    echo "\n📋 Available roles:\n";
    echo "   - admin: Full system access\n";
    echo "   - databasemanager: Database management access\n";
    echo "   - contentmanager: Content management access\n";
    echo "   - rolemanager: User and role management access\n";
    echo "   - user: Standard user access\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
