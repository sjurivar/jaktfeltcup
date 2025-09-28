<?php
/**
 * Command Line User Creation Script
 * Creates a user with command line arguments
 * 
 * Usage: 
 *   php create_user_cli.php --username=myuser --password=mypass --email=user@example.com --role=admin
 *   php create_user_cli.php -u myuser -p mypass -e user@example.com -r admin -f "John" -l "Doe"
 */

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Parse command line arguments
$options = getopt("u:p:e:r:f:l:h", [
    "username:",
    "password:", 
    "email:",
    "role:",
    "firstname:",
    "lastname:",
    "help"
]);

// Show help if requested
if (isset($options['h']) || isset($options['help'])) {
    echo "👤 User Creation Script\n";
    echo str_repeat("=", 50) . "\n\n";
    echo "Usage:\n";
    echo "  php create_user_cli.php [options]\n\n";
    echo "Options:\n";
    echo "  -u, --username     Username (required)\n";
    echo "  -p, --password     Password (required)\n";
    echo "  -e, --email        Email address (required)\n";
    echo "  -r, --role         Role (required)\n";
    echo "  -f, --firstname    First name (optional)\n";
    echo "  -l, --lastname     Last name (optional)\n";
    echo "  -h, --help         Show this help\n\n";
    echo "Examples:\n";
    echo "  php create_user_cli.php -u admin -p password123 -e admin@example.com -r admin\n";
    echo "  php create_user_cli.php --username=user1 --password=mypass --email=user1@example.com --role=user --firstname=John --lastname=Doe\n";
    exit(0);
}

// Get values from command line arguments
$username = $options['u'] ?? $options['username'] ?? null;
$password = $options['p'] ?? $options['password'] ?? null;
$email = $options['e'] ?? $options['email'] ?? null;
$role = $options['r'] ?? $options['role'] ?? null;
$firstName = $options['f'] ?? $options['firstname'] ?? 'User';
$lastName = $options['l'] ?? $options['lastname'] ?? 'User';

// Validate required parameters
if (!$username || !$password || !$email || !$role) {
    echo "❌ Missing required parameters.\n";
    echo "Use -h or --help for usage information.\n";
    exit(1);
}

echo "👤 Creating user: $username\n";
echo str_repeat("-", 50) . "\n";

try {
    // Check if user already exists
    $existingUser = $database->queryOne(
        "SELECT id FROM jaktfelt_users WHERE username = ? OR email = ?",
        [$username, $email]
    );
    
    if ($existingUser) {
        echo "❌ User with username '$username' or email '$email' already exists.\n";
        exit(1);
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "❌ Invalid email format: $email\n";
        exit(1);
    }
    
    // Check if role exists
    $roleData = $database->queryOne(
        "SELECT id FROM jaktfelt_roles WHERE role_name = ?",
        [$role]
    );
    
    if (!$roleData) {
        echo "❌ Role '$role' does not exist.\n";
        
        // Show available roles
        $availableRoles = $database->queryAll("SELECT role_name FROM jaktfelt_roles ORDER BY role_name");
        echo "Available roles: " . implode(', ', array_column($availableRoles, 'role_name')) . "\n";
        exit(1);
    }
    
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $userId = $database->execute(
        "INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at, is_test_data) VALUES (?, ?, ?, ?, ?, 1, NOW(), 0)",
        [$username, $firstName, $lastName, $email, $passwordHash]
    );
    
    if ($userId) {
        echo "✅ User created successfully (ID: $userId)\n";
        
        // Assign role
        $database->execute(
            "INSERT INTO jaktfelt_user_roles (user_id, role_id, is_test_data) VALUES (?, ?, 0)",
            [$userId, $roleData['id']]
        );
        
        echo "✅ Role '$role' assigned to user\n";
        
        echo "\n📋 User Details:\n";
        echo str_repeat("-", 50) . "\n";
        echo "Username: $username\n";
        echo "Name: $firstName $lastName\n";
        echo "Email: $email\n";
        echo "Role: $role\n";
        echo "Password: $password\n";
        echo str_repeat("-", 50) . "\n";
        
        echo "\n🔗 Login URLs:\n";
        if (in_array($role, ['admin', 'databasemanager', 'contentmanager', 'rolemanager'])) {
            echo "   Admin: http://localhost/jaktfeltcup/admin\n";
        }
        echo "   Login: http://localhost/jaktfeltcup/login\n";
        
    } else {
        echo "❌ Failed to create user\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
