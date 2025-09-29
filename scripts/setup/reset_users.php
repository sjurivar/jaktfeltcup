<?php
/**
 * Reset Users Script
 * 
 * This script removes all test users and allows you to start fresh
 * with the user creation process.
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "🗑️  Reset Users Script\n";
echo "======================\n\n";

try {
    echo "🔍 Checking current users...\n";
    
    $users = $database->queryAll("SELECT id, username, email FROM jaktfelt_users ORDER BY username");
    
    if (empty($users)) {
        echo "✅ No users found in database.\n";
        echo "You can now create new users.\n";
        exit(0);
    }
    
    echo "📊 Found " . count($users) . " users:\n";
    foreach ($users as $user) {
        echo "  - {$user['username']} ({$user['email']})\n";
    }
    
    echo "\n⚠️  WARNING: This will delete ALL users from the database!\n";
    echo "Are you sure you want to continue? (y/N): ";
    
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    fclose($handle);
    
    if (trim(strtolower($line)) !== 'y') {
        echo "❌ Operation cancelled.\n";
        exit(0);
    }
    
    echo "\n🗑️  Deleting users...\n";
    
    // Delete user roles first (foreign key constraint)
    $deletedRoles = $database->query("DELETE FROM jaktfelt_user_roles");
    echo "✅ Deleted user roles\n";
    
    // Delete users
    $deletedUsers = $database->query("DELETE FROM jaktfelt_users");
    echo "✅ Deleted users\n";
    
    echo "\n🎯 Next steps:\n";
    echo "=============\n";
    echo "1. Create new users: php scripts/setup/create_working_users.php\n";
    echo "2. Or create admin only: php scripts/setup/create_admin_quick.php\n";
    echo "3. Test login: php scripts/setup/test_login.php\n";
    
    echo "\n✅ User reset completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
