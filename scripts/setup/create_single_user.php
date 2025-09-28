<?php
/**
 * Interactive User Creation Script
 * Creates a user with custom username, password, and user data
 * 
 * Usage: php create_single_user.php
 */

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

echo "👤 Interactive User Creation\n";
echo str_repeat("=", 50) . "\n\n";

// Function to get user input with validation
function getInput($prompt, $required = true, $options = null) {
    while (true) {
        echo $prompt;
        $input = trim(fgets(STDIN));
        
        if ($required && empty($input)) {
            echo "❌ This field is required. Please try again.\n";
            continue;
        }
        
        if ($options && !in_array($input, $options)) {
            echo "❌ Invalid option. Please choose from: " . implode(', ', $options) . "\n";
            continue;
        }
        
        return $input;
    }
}

// Function to get password with confirmation
function getPassword() {
    while (true) {
        echo "Enter password: ";
        $password = trim(fgets(STDIN));
        
        if (empty($password)) {
            echo "❌ Password is required.\n";
            continue;
        }
        
        if (strlen($password) < 6) {
            echo "❌ Password must be at least 6 characters long.\n";
            continue;
        }
        
        echo "Confirm password: ";
        $confirmPassword = trim(fgets(STDIN));
        
        if ($password !== $confirmPassword) {
            echo "❌ Passwords do not match. Please try again.\n";
            continue;
        }
        
        return $password;
    }
}

try {
    // Get user input with validation
    $username = getInput("Enter username: ");
    
    // Check if username already exists
    $existingUser = $database->queryOne(
        "SELECT id FROM jaktfelt_users WHERE username = ?",
        [$username]
    );
    
    if ($existingUser) {
        echo "❌ Username '$username' already exists. Please choose a different username.\n";
        exit(1);
    }
    
    $firstName = getInput("Enter first name: ");
    $lastName = getInput("Enter last name: ");
    
    $email = getInput("Enter email: ");
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "❌ Invalid email format.\n";
        exit(1);
    }
    
    // Check if email already exists
    $existingEmail = $database->queryOne(
        "SELECT id FROM jaktfelt_users WHERE email = ?",
        [$email]
    );
    
    if ($existingEmail) {
        echo "❌ Email '$email' already exists. Please choose a different email.\n";
        exit(1);
    }
    
    $password = getPassword();
    
    // Get available roles
    $availableRoles = $database->queryAll("SELECT role_name FROM jaktfelt_roles ORDER BY role_name");
    $roleOptions = array_column($availableRoles, 'role_name');
    
    echo "\nAvailable roles:\n";
    foreach ($roleOptions as $i => $role) {
        echo "  " . ($i + 1) . ". $role\n";
    }
    
    $role = getInput("\nEnter role: ", true, $roleOptions);
    
    // Validate inputs
    if (empty($username) || empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($role)) {
        echo "❌ All fields are required. Exiting.\n";
        exit(1);
    }
    
    // Check if user already exists
    $existingUser = $database->queryOne(
        "SELECT id FROM jaktfelt_users WHERE username = ? OR email = ?",
        [$username, $email]
    );
    
    if ($existingUser) {
        echo "❌ User with username '$username' or email '$email' already exists.\n";
        exit(1);
    }
    
    // Check if role exists
    $roleData = $database->queryOne(
        "SELECT id FROM jaktfelt_roles WHERE role_name = ?",
        [$role]
    );
    
    if (!$roleData) {
        echo "❌ Role '$role' does not exist.\n";
        echo "Available roles: admin, databasemanager, contentmanager, rolemanager, user\n";
        exit(1);
    }
    
    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    echo "\n🔄 Creating user...\n";
    
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
