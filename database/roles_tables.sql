-- Role Management Tables for Jaktfeltcup

-- Roles table
CREATE TABLE IF NOT EXISTS jaktfelt_roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    INDEX idx_roles_test_data (is_test_data)
);

-- User roles junction table
CREATE TABLE IF NOT EXISTS jaktfelt_user_roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    assigned_by INT,
    is_test_data BOOLEAN DEFAULT FALSE,
    UNIQUE KEY unique_user_role (user_id, role_id),
    INDEX idx_user_roles_test_data (is_test_data),
    FOREIGN KEY (user_id) REFERENCES jaktfelt_users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES jaktfelt_roles(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES jaktfelt_users(id) ON DELETE SET NULL
);

-- Insert default roles
INSERT INTO jaktfelt_roles (role_name, description, is_test_data) VALUES
('admin', 'Full system administrator with access to all features', TRUE),
('databasemanager', 'Can access database management and maintenance tools', TRUE),
('contentmanager', 'Can manage content including news and sponsors', TRUE),
('rolemanager', 'Can manage users and assign roles', TRUE),
('user', 'Standard user with basic access', TRUE);

-- Assign admin role to first user (if exists)
INSERT INTO jaktfelt_user_roles (user_id, role_id, is_test_data)
SELECT u.id, r.id, TRUE
FROM jaktfelt_users u, jaktfelt_roles r
WHERE u.id = 1 AND r.role_name = 'admin'
AND NOT EXISTS (
    SELECT 1 FROM jaktfelt_user_roles ur 
    WHERE ur.user_id = u.id AND ur.role_id = r.id
);
