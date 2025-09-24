<?php
/**
 * Setup Database Schema (Ordered)
 * Create database and import schema in correct order
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Setup Database (Ordered) - Database Admin';
$current_page = 'admin';
$body_class = 'bg-light';

// Include header
include_header();

// Database configuration
$host = $db_config['host'];
$user = $db_config['user'];
$password = $db_config['password'];
$dbname = $db_config['name'];

try {
    // Connect to MySQL (without database)
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<div class='container mt-4'>";
    echo "<h1><i class='fas fa-database me-2'></i>Setup Database Schema (Ordered)</h1>";
    echo "<p class='lead'>Create database and import schema in correct order</p>";
    
    // Check if database exists
    $result = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    if ($result->rowCount() > 0) {
        echo "<div class='alert alert-warning'>";
        echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Database Already Exists</h4>";
        echo "<p>Database <code>$dbname</code> already exists.</p>";
        echo "</div>";
    } else {
        // Create database
        $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "<div class='alert alert-success'>";
        echo "<h4><i class='fas fa-check me-2'></i>Database Created</h4>";
        echo "<p>Database <code>$dbname</code> has been created successfully.</p>";
        echo "</div>";
    }
    
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Define tables in correct order (parents first, children last)
    $tableDefinitions = [
        // Parent tables (no foreign keys to other tables)
        'jaktfelt_users' => "
            CREATE TABLE jaktfelt_users (
                id INT PRIMARY KEY AUTO_INCREMENT,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                phone VARCHAR(20),
                date_of_birth DATE,
                address TEXT,
                role ENUM('admin', 'organizer', 'participant') DEFAULT 'participant',
                is_active BOOLEAN DEFAULT TRUE,
                email_verified BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_username (username),
                INDEX idx_email (email),
                INDEX idx_role (role),
                INDEX idx_test_data (is_test_data)
            )
        ",
        
        'jaktfelt_seasons' => "
            CREATE TABLE jaktfelt_seasons (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                year INT NOT NULL,
                is_active BOOLEAN DEFAULT FALSE,
                start_date DATE,
                end_date DATE,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_year (year),
                INDEX idx_active (is_active),
                INDEX idx_test_data (is_test_data)
            )
        ",
        
        'jaktfelt_categories' => "
            CREATE TABLE jaktfelt_categories (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                description TEXT,
                min_age INT,
                max_age INT,
                weapon_type ENUM('Rifle', 'Pistol', 'Shotgun') DEFAULT 'Rifle',
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_name (name),
                INDEX idx_weapon_type (weapon_type),
                INDEX idx_active (is_active),
                INDEX idx_test_data (is_test_data)
            )
        ",
        
        'jaktfelt_point_systems' => "
            CREATE TABLE jaktfelt_point_systems (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_name (name),
                INDEX idx_active (is_active),
                INDEX idx_test_data (is_test_data)
            )
        ",
        
        // Child tables (with foreign keys)
        'jaktfelt_competitions' => "
            CREATE TABLE jaktfelt_competitions (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                date DATE NOT NULL,
                location VARCHAR(100),
                description TEXT,
                max_participants INT,
                registration_deadline DATETIME,
                status ENUM('upcoming', 'open', 'closed', 'completed', 'cancelled') DEFAULT 'upcoming',
                season_id INT,
                organizer_id INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_date (date),
                INDEX idx_status (status),
                INDEX idx_season (season_id),
                INDEX idx_organizer (organizer_id),
                INDEX idx_test_data (is_test_data),
                FOREIGN KEY (season_id) REFERENCES jaktfelt_seasons(id) ON DELETE CASCADE,
                FOREIGN KEY (organizer_id) REFERENCES jaktfelt_users(id) ON DELETE RESTRICT
            )
        ",
        
        'jaktfelt_competition_categories' => "
            CREATE TABLE jaktfelt_competition_categories (
                id INT PRIMARY KEY AUTO_INCREMENT,
                competition_id INT NOT NULL,
                category_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_competition (competition_id),
                INDEX idx_category (category_id),
                INDEX idx_test_data (is_test_data),
                FOREIGN KEY (competition_id) REFERENCES jaktfelt_competitions(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES jaktfelt_categories(id) ON DELETE CASCADE
            )
        ",
        
        'jaktfelt_registrations' => "
            CREATE TABLE jaktfelt_registrations (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                competition_id INT NOT NULL,
                category_id INT NOT NULL,
                registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
                notes TEXT,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_user (user_id),
                INDEX idx_competition (competition_id),
                INDEX idx_category (category_id),
                INDEX idx_status (status),
                INDEX idx_test_data (is_test_data),
                FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE CASCADE,
                FOREIGN KEY (competition_id) REFERENCES jaktfelt_competitions(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES jaktfelt_categories(id) ON DELETE CASCADE
            )
        ",
        
        'jaktfelt_results' => "
            CREATE TABLE jaktfelt_results (
                id INT PRIMARY KEY AUTO_INCREMENT,
                competition_id INT NOT NULL,
                user_id INT NOT NULL,
                category_id INT NOT NULL,
                score INT,
                position INT,
                points_awarded INT,
                is_walk_in BOOLEAN DEFAULT FALSE,
                notes TEXT,
                entered_by INT,
                entered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_competition (competition_id),
                INDEX idx_user (user_id),
                INDEX idx_category (category_id),
                INDEX idx_position (position),
                INDEX idx_entered_by (entered_by),
                INDEX idx_test_data (is_test_data),
                FOREIGN KEY (competition_id) REFERENCES jaktfelt_competitions(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE CASCADE,
                FOREIGN KEY (category_id) REFERENCES jaktfelt_categories(id) ON DELETE CASCADE,
                FOREIGN KEY (entered_by) REFERENCES jaktfelt_users(id) ON DELETE RESTRICT
            )
        ",
        
        'jaktfelt_point_rules' => "
            CREATE TABLE jaktfelt_point_rules (
                id INT PRIMARY KEY AUTO_INCREMENT,
                point_system_id INT NOT NULL,
                position INT NOT NULL,
                points INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_point_system (point_system_id),
                INDEX idx_position (position),
                INDEX idx_test_data (is_test_data),
                FOREIGN KEY (point_system_id) REFERENCES jaktfelt_point_systems(id) ON DELETE CASCADE
            )
        ",
        
        'jaktfelt_season_point_systems' => "
            CREATE TABLE jaktfelt_season_point_systems (
                id INT PRIMARY KEY AUTO_INCREMENT,
                season_id INT NOT NULL,
                point_system_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_season (season_id),
                INDEX idx_point_system (point_system_id),
                INDEX idx_test_data (is_test_data),
                FOREIGN KEY (season_id) REFERENCES jaktfelt_seasons(id) ON DELETE CASCADE,
                FOREIGN KEY (point_system_id) REFERENCES jaktfelt_point_systems(id) ON DELETE CASCADE
            )
        ",
        
        'jaktfelt_email_verifications' => "
            CREATE TABLE jaktfelt_email_verifications (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                code VARCHAR(6) NOT NULL,
                expires_at TIMESTAMP NOT NULL,
                used_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_user (user_id),
                INDEX idx_code (code),
                INDEX idx_expires (expires_at),
                INDEX idx_test_data (is_test_data),
                FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE CASCADE
            )
        ",
        
        'jaktfelt_notifications' => "
            CREATE TABLE jaktfelt_notifications (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                type ENUM('email', 'sms', 'push') NOT NULL,
                subject VARCHAR(200),
                message TEXT NOT NULL,
                sent_at TIMESTAMP NULL,
                status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_user (user_id),
                INDEX idx_type (type),
                INDEX idx_status (status),
                INDEX idx_sent_at (sent_at),
                INDEX idx_test_data (is_test_data),
                FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE CASCADE
            )
        ",
        
        'jaktfelt_offline_sync' => "
            CREATE TABLE jaktfelt_offline_sync (
                id INT PRIMARY KEY AUTO_INCREMENT,
                table_name VARCHAR(50) NOT NULL,
                record_id INT NOT NULL,
                action ENUM('create', 'update', 'delete') NOT NULL,
                data JSON,
                sync_status ENUM('pending', 'synced', 'failed') DEFAULT 'pending',
                synced_at TIMESTAMP NULL,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_table_record (table_name, record_id),
                INDEX idx_sync_status (sync_status),
                INDEX idx_test_data (is_test_data)
            )
        ",
        
        'jaktfelt_audit_log' => "
            CREATE TABLE jaktfelt_audit_log (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT,
                table_name VARCHAR(50) NOT NULL,
                record_id INT NOT NULL,
                action ENUM('create', 'update', 'delete') NOT NULL,
                old_values JSON,
                new_values JSON,
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                is_test_data BOOLEAN DEFAULT FALSE,
                INDEX idx_user (user_id),
                INDEX idx_table_record (table_name, record_id),
                INDEX idx_action (action),
                INDEX idx_created_at (created_at),
                INDEX idx_test_data (is_test_data),
                FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE SET NULL
            )
        "
    ];
    
    echo "<div class='card'>";
    echo "<div class='card-header'>";
    echo "<h5><i class='fas fa-cogs me-2'></i>Table Creation (Ordered)</h5>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    $createdCount = 0;
    $errorCount = 0;
    
    foreach ($tableDefinitions as $tableName => $createStatement) {
        try {
            $pdo->exec($createStatement);
            $createdCount++;
            echo "<p class='text-success'><i class='fas fa-check me-2'></i>Created table: <code>$tableName</code></p>";
        } catch (PDOException $e) {
            $errorCount++;
            echo "<p class='text-danger'><i class='fas fa-times me-2'></i>Error creating table <code>$tableName</code>: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "</div>";
    echo "</div>";
    
    // Summary
    echo "<div class='alert alert-info mt-4'>";
    echo "<h5><i class='fas fa-info-circle me-2'></i>Table Creation Summary</h5>";
    echo "<ul>";
    echo "<li><strong>Tables created:</strong> $createdCount</li>";
    echo "<li><strong>Errors:</strong> $errorCount</li>";
    echo "</ul>";
    echo "</div>";
    
    if ($createdCount > 0) {
        echo "<div class='alert alert-success'>";
        echo "<h5><i class='fas fa-check me-2'></i>Database Schema Created Successfully</h5>";
        echo "<p>All tables have been created in the correct order.</p>";
        echo "</div>";
    }
    
    // Actions
    echo "<div class='mt-4'>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo " <a href='check_tables.php' class='btn btn-info'><i class='fas fa-list me-2'></i>Check Tables</a>";
    if ($createdCount > 0) {
        echo " <a href='import_sample_data.php' class='btn btn-primary'><i class='fas fa-upload me-2'></i>Import Sample Data</a>";
    }
    echo "</div>";
    
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='container mt-4'>";
    echo "<div class='alert alert-danger'>";
    echo "<h4><i class='fas fa-exclamation-triangle me-2'></i>Database Error</h4>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
    echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-arrow-left me-2'></i>Back to Admin</a>";
    echo "</div>";
}

include_footer();
?>
