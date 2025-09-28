<?php
/**
 * JSON User Creation Script
 * Creates multiple users from a JSON configuration file
 * 
 * Usage: php create_users_from_json.php users.json
 * 
 * JSON format:
 * {
 *   "users": [
 *     {
 *       "username": "admin",
 *       "first_name": "Admin",
 *       "last_name": "User",
 *       "email": "admin@example.com",
 *       "password": "password123",
 *       "role": "admin"
 *     }
 *   ]
 * }
 */

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Check if JSON file is provided
if ($argc < 2) {
    echo "❌ Usage: php create_users_from_json.php <json_file>\n";
    echo "Example: php create_users_from_json.php users.json\n";
    exit(1);
}

$jsonFile = $argv[1];

if (!file_exists($jsonFile)) {
    echo "❌ JSON file not found: $jsonFile\n";
    exit(1);
}

echo "📁 Creating users from JSON: $jsonFile\n";
echo str_repeat("=", 50) . "\n\n";

try {
    $jsonContent = file_get_contents($jsonFile);
    $data = json_decode($jsonContent, true);
    
    if (!$data || !isset($data['users'])) {
        echo "❌ Invalid JSON format. Expected 'users' array.\n";
        exit(1);
    }
    
    $users = $data['users'];
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($users as $index => $userData) {
        $userNumber = $index + 1;
        echo "Processing user $userNumber: " . ($userData['username'] ?? 'Unknown') . "... ";
        
        // Validate required fields
        $requiredFields = ['username', 'email', 'password', 'role'];
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (!isset($userData[$field]) || empty($userData[$field])) {
                $missingFields[] = $field;
            }
        }
        
        if (!empty($missingFields)) {
            echo "❌ Missing fields: " . implode(', ', $missingFields) . "\n";
            $errorCount++;
            continue;
        }
        
        $username = $userData['username'];
        $firstName = $userData['first_name'] ?? 'User';
        $lastName = $userData['last_name'] ?? 'User';
        $email = $userData['email'];
        $password = $userData['password'];
        $role = $userData['role'];
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "❌ Invalid email format\n";
            $errorCount++;
            continue;
        }
        
        // Check if user already exists
        $existingUser = $database->queryOne(
            "SELECT id FROM jaktfelt_users WHERE username = ? OR email = ?",
            [$username, $email]
        );
        
        if ($existingUser) {
            echo "❌ User already exists\n";
            $errorCount++;
            continue;
        }
        
        // Check if role exists
        $roleData = $database->queryOne(
            "SELECT id FROM jaktfelt_roles WHERE role_name = ?",
            [$role]
        );
        
        if (!$roleData) {
            echo "❌ Role '$role' not found\n";
            $errorCount++;
            continue;
        }
        
        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $userId = $database->execute(
            "INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at, is_test_data) VALUES (?, ?, ?, ?, ?, 1, NOW(), 0)",
            [$username, $firstName, $lastName, $email, $passwordHash]
        );
        
        if ($userId) {
            // Assign role
            $database->execute(
                "INSERT INTO jaktfelt_user_roles (user_id, role_id, is_test_data) VALUES (?, ?, 0)",
                [$userId, $roleData['id']]
            );
            
            echo "✅ Created successfully\n";
            $successCount++;
        } else {
            echo "❌ Failed to create user\n";
            $errorCount++;
        }
    }
    
    echo "\n📊 Summary:\n";
    echo str_repeat("-", 50) . "\n";
    echo "Total users processed: " . count($users) . "\n";
    echo "Users created successfully: $successCount\n";
    echo "Errors: $errorCount\n";
    echo str_repeat("-", 50) . "\n";
    
    if ($successCount > 0) {
        echo "\n🎉 User creation completed!\n";
        echo "🔗 Login: http://localhost/jaktfeltcup/login\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
