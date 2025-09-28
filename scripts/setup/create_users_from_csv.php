<?php
/**
 * CSV User Creation Script
 * Creates multiple users from a CSV file
 * 
 * Usage: php create_users_from_csv.php users.csv
 * 
 * CSV format:
 * username,first_name,last_name,email,password,role
 * admin,Admin,User,admin@example.com,password123,admin
 * user1,John,Doe,user1@example.com,mypassword,user
 */

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Check if CSV file is provided
if ($argc < 2) {
    echo "❌ Usage: php create_users_from_csv.php <csv_file>\n";
    echo "Example: php create_users_from_csv.php users.csv\n";
    exit(1);
}

$csvFile = $argv[1];

if (!file_exists($csvFile)) {
    echo "❌ CSV file not found: $csvFile\n";
    exit(1);
}

echo "📁 Creating users from CSV: $csvFile\n";
echo str_repeat("=", 50) . "\n\n";

try {
    $handle = fopen($csvFile, 'r');
    if (!$handle) {
        echo "❌ Could not open CSV file: $csvFile\n";
        exit(1);
    }
    
    $lineNumber = 0;
    $successCount = 0;
    $errorCount = 0;
    
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $lineNumber++;
        
        // Skip header row
        if ($lineNumber === 1) {
            continue;
        }
        
        // Validate CSV row
        if (count($data) < 6) {
            echo "❌ Line $lineNumber: Invalid CSV format. Expected 6 columns.\n";
            $errorCount++;
            continue;
        }
        
        $username = trim($data[0]);
        $firstName = trim($data[1]);
        $lastName = trim($data[2]);
        $email = trim($data[3]);
        $password = trim($data[4]);
        $role = trim($data[5]);
        
        echo "Processing user: $username... ";
        
        // Validate required fields
        if (empty($username) || empty($email) || empty($password) || empty($role)) {
            echo "❌ Missing required fields\n";
            $errorCount++;
            continue;
        }
        
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
    
    fclose($handle);
    
    echo "\n📊 Summary:\n";
    echo str_repeat("-", 50) . "\n";
    echo "Total lines processed: " . ($lineNumber - 1) . "\n";
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
