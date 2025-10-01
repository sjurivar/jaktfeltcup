-- Add Rounds and Organizers tables for Nasjonal 15m Jaktfeltcup
-- This supports the structure: 4 rounds, each with multiple events

-- Rounds table (4 rounds per season)
CREATE TABLE IF NOT EXISTS jaktfelt_rounds (
    id INT PRIMARY KEY AUTO_INCREMENT,
    season_id INT NOT NULL,
    round_number INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    result_deadline DATE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (season_id) REFERENCES jaktfelt_seasons(id) ON DELETE CASCADE,
    UNIQUE KEY unique_season_round (season_id, round_number),
    INDEX idx_season (season_id),
    INDEX idx_active (is_active),
    INDEX idx_test_data (is_test_data)
);

-- Organizers table (clubs/organizations that arrange events)
CREATE TABLE IF NOT EXISTS jaktfelt_organizers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    organization_type ENUM('skytterlag', 'njff_lokallag', 'dfs_lokallag', 'annet') DEFAULT 'skytterlag',
    contact_person VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    postal_code VARCHAR(10),
    city VARCHAR(100),
    website VARCHAR(255),
    description TEXT,
    logo_filename VARCHAR(255),
    logo_url VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    INDEX idx_active (is_active),
    INDEX idx_organization_type (organization_type),
    INDEX idx_test_data (is_test_data)
);

-- Update competitions table to link to rounds and organizers
-- Check if columns exist first, then add them
SET @dbname = DATABASE();
SET @tablename = 'jaktfelt_competitions';

-- Add round_id column if not exists
SET @round_id_exists = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = @dbname 
    AND TABLE_NAME = @tablename 
    AND COLUMN_NAME = 'round_id'
);

SET @sql_add_round_id = IF(@round_id_exists = 0,
    'ALTER TABLE jaktfelt_competitions ADD COLUMN round_id INT AFTER season_id',
    'SELECT "Column round_id already exists" AS message'
);

PREPARE stmt FROM @sql_add_round_id;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add organizer_id column if not exists
SET @organizer_id_exists = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = @dbname 
    AND TABLE_NAME = @tablename 
    AND COLUMN_NAME = 'organizer_id'
);

SET @sql_add_organizer_id = IF(@organizer_id_exists = 0,
    'ALTER TABLE jaktfelt_competitions ADD COLUMN organizer_id INT AFTER round_id',
    'SELECT "Column organizer_id already exists" AS message'
);

PREPARE stmt FROM @sql_add_organizer_id;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Sample data for current season (2024-2025)
-- Insert season
INSERT IGNORE INTO jaktfelt_seasons (name, year, is_active, start_date, end_date, is_test_data) 
VALUES ('Nasjonal 15m Jaktfeltcup 2024-2025', 2024, TRUE, '2024-11-01', '2025-02-28', FALSE);

-- Insert rounds (4 rounds) - using subquery to get season_id
INSERT IGNORE INTO jaktfelt_rounds (season_id, round_number, name, start_date, end_date, result_deadline, is_test_data) 
SELECT id, 1, 'Runde 1', '2024-11-01', '2024-11-30', '2024-12-05', FALSE FROM jaktfelt_seasons WHERE year = 2024 LIMIT 1;

INSERT IGNORE INTO jaktfelt_rounds (season_id, round_number, name, start_date, end_date, result_deadline, is_test_data) 
SELECT id, 2, 'Runde 2', '2024-12-01', '2024-12-31', '2025-01-05', FALSE FROM jaktfelt_seasons WHERE year = 2024 LIMIT 1;

INSERT IGNORE INTO jaktfelt_rounds (season_id, round_number, name, start_date, end_date, result_deadline, is_test_data) 
SELECT id, 3, 'Runde 3', '2025-01-01', '2025-01-31', '2025-02-05', FALSE FROM jaktfelt_seasons WHERE year = 2024 LIMIT 1;

INSERT IGNORE INTO jaktfelt_rounds (season_id, round_number, name, start_date, end_date, result_deadline, is_test_data) 
SELECT id, 4, 'Runde 4 (Finale)', '2025-02-01', '2025-02-28', '2025-03-05', FALSE FROM jaktfelt_seasons WHERE year = 2024 LIMIT 1;

-- Sample organizers (these should be replaced with real data)
INSERT IGNORE INTO jaktfelt_organizers (name, organization_type, contact_person, email, phone, city, is_test_data) VALUES
('Eksempel Skytterlag', 'skytterlag', 'Ola Nordmann', 'kontakt@eksempel.no', '12345678', 'Oslo', TRUE),
('Eksempel NJFF Lokallag', 'njff_lokallag', 'Kari Nordmann', 'post@njff-eksempel.no', '87654321', 'Bergen', TRUE),
('Eksempel DFS Lokallag', 'dfs_lokallag', 'Per Hansen', 'info@dfs-eksempel.no', '11223344', 'Trondheim', TRUE);

