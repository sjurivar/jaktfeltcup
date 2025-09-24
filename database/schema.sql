-- Jaktfeltcup Database Schema
-- Created for managing shooting competition administration

-- Users table with role-based access
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
    role ENUM('participant', 'organizer', 'admin') DEFAULT 'participant',
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    phone_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active),
    INDEX idx_test_data (is_test_data)
);

-- Seasons table for multi-year support
CREATE TABLE jaktfelt_seasons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    year YEAR NOT NULL,
    is_active BOOLEAN DEFAULT FALSE,
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    UNIQUE KEY unique_year (year),
    INDEX idx_active (is_active),
    INDEX idx_test_data (is_test_data)
);

-- Competitions table
CREATE TABLE jaktfelt_competitions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    season_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(100) NOT NULL,
    competition_date DATE NOT NULL,
    registration_start DATE NOT NULL,
    registration_end DATE NOT NULL,
    max_participants INT,
    is_published BOOLEAN DEFAULT FALSE,
    is_locked BOOLEAN DEFAULT FALSE,
    organizer_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (season_id) REFERENCES jaktfelt_seasons(id) ON DELETE CASCADE,
    FOREIGN KEY (organizer_id) REFERENCES jaktfelt_users(id) ON DELETE RESTRICT,
    INDEX idx_season (season_id),
    INDEX idx_date (competition_date),
    INDEX idx_registration (registration_start, registration_end),
    INDEX idx_published (is_published),
    INDEX idx_test_data (is_test_data)
);

-- Categories for competitions (age groups, weapon types, etc.)
CREATE TABLE jaktfelt_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    min_age INT,
    max_age INT,
    weapon_type VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    INDEX idx_active (is_active),
    INDEX idx_test_data (is_test_data)
);

-- Competition categories (many-to-many)
CREATE TABLE jaktfelt_competition_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    competition_id INT NOT NULL,
    category_id INT NOT NULL,
    max_participants INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (competition_id) REFERENCES jaktfelt_competitions(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES jaktfelt_categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_competition_category (competition_id, category_id),
    INDEX idx_test_data (is_test_data)
);

-- Registrations table
CREATE TABLE jaktfelt_registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    competition_id INT NOT NULL,
    category_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'cancelled', 'no_show') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE CASCADE,
    FOREIGN KEY (competition_id) REFERENCES jaktfelt_competitions(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES jaktfelt_categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_competition (user_id, competition_id),
    INDEX idx_status (status),
    INDEX idx_competition (competition_id),
    INDEX idx_test_data (is_test_data)
);

-- Results table
CREATE TABLE jaktfelt_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    competition_id INT NOT NULL,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    score INT NOT NULL,
    position INT,
    points_awarded INT DEFAULT 0,
    is_walk_in BOOLEAN DEFAULT FALSE,
    notes TEXT,
    entered_by INT NOT NULL,
    entered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (competition_id) REFERENCES jaktfelt_competitions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES jaktfelt_categories(id) ON DELETE CASCADE,
    FOREIGN KEY (entered_by) REFERENCES jaktfelt_users(id) ON DELETE RESTRICT,
    UNIQUE KEY unique_user_competition_category (user_id, competition_id, category_id),
    INDEX idx_competition (competition_id),
    INDEX idx_user (user_id),
    INDEX idx_score (score),
    INDEX idx_test_data (is_test_data)
);

-- Point system configuration (flexible scoring)
CREATE TABLE jaktfelt_point_systems (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    INDEX idx_active (is_active),
    INDEX idx_test_data (is_test_data)
);

-- Point system rules
CREATE TABLE jaktfelt_point_rules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    point_system_id INT NOT NULL,
    position INT NOT NULL,
    points INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (point_system_id) REFERENCES jaktfelt_point_systems(id) ON DELETE CASCADE,
    UNIQUE KEY unique_system_position (point_system_id, position),
    INDEX idx_position (position),
    INDEX idx_test_data (is_test_data)
);

-- Season point system assignment
CREATE TABLE jaktfelt_season_point_systems (
    id INT PRIMARY KEY AUTO_INCREMENT,
    season_id INT NOT NULL,
    point_system_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (season_id) REFERENCES jaktfelt_seasons(id) ON DELETE CASCADE,
    FOREIGN KEY (point_system_id) REFERENCES jaktfelt_point_systems(id) ON DELETE CASCADE,
    UNIQUE KEY unique_season_system (season_id, point_system_id),
    INDEX idx_test_data (is_test_data)
);

-- Email verification codes
CREATE TABLE jaktfelt_email_verifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    verification_code VARCHAR(32) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    is_used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    used_at TIMESTAMP NULL,
    is_test_data BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_code (verification_code),
    INDEX idx_expires (expires_at),
    INDEX idx_used (is_used),
    INDEX idx_test_data (is_test_data)
);

-- Notifications table
CREATE TABLE jaktfelt_notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    type ENUM('email', 'sms', 'system') NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_type (type),
    INDEX idx_test_data (is_test_data)
);

-- Offline sync table for mobile/offline functionality
CREATE TABLE jaktfelt_offline_sync (
    id INT PRIMARY KEY AUTO_INCREMENT,
    table_name VARCHAR(50) NOT NULL,
    record_id INT NOT NULL,
    action ENUM('create', 'update', 'delete') NOT NULL,
    data JSON,
    sync_status ENUM('pending', 'synced', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    synced_at TIMESTAMP NULL,
    is_test_data BOOLEAN DEFAULT FALSE,
    INDEX idx_table_record (table_name, record_id),
    INDEX idx_sync_status (sync_status),
    INDEX idx_test_data (is_test_data)
);

-- Audit log for tracking changes
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
    FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_table_record (table_name, record_id),
    INDEX idx_created (created_at),
    INDEX idx_test_data (is_test_data)
);

-- Insert default data
INSERT INTO jaktfelt_seasons (name, year, is_active, start_date, end_date) VALUES 
('Jaktfeltcup 2024', 2024, TRUE, '2024-01-01', '2024-12-31');

INSERT INTO jaktfelt_categories (name, description, min_age, max_age, weapon_type) VALUES 
('Senior', 'Senior klasse', 18, 99, 'Rifle'),
('Junior', 'Junior klasse', 12, 17, 'Rifle'),
('Veteran', 'Veteran klasse', 50, 99, 'Rifle');

INSERT INTO jaktfelt_point_systems (name, description, is_active) VALUES 
('Standard', 'Standard poengsystem: 1.plass=100p, 2.plass=90p, osv.', TRUE);

INSERT INTO jaktfelt_point_rules (point_system_id, position, points) VALUES 
(1, 1, 100),
(1, 2, 90),
(1, 3, 80),
(1, 4, 70),
(1, 5, 60),
(1, 6, 50),
(1, 7, 40),
(1, 8, 30),
(1, 9, 20),
(1, 10, 10);

INSERT INTO jaktfelt_season_point_systems (season_id, point_system_id) VALUES (1, 1);
