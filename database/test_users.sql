-- Test Users with Different Roles
-- Creates 3 test users, each with a different role for testing

-- Insert test users with password: password123
-- Note: These hashes are for password 'password123'
INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at, is_test_data) VALUES
('db.manager', 'Database', 'Manager', 'db.manager@jaktfeltcup.no', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 1, NOW(), 1),
('content.manager', 'Content', 'Manager', 'content.manager@jaktfeltcup.no', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 1, NOW(), 1),
('role.manager', 'Role', 'Manager', 'role.manager@jaktfeltcup.no', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 1, NOW(), 1);

-- Get user IDs (assuming they are the last 3 users inserted)
SET @db_manager_id = (SELECT id FROM jaktfelt_users WHERE username = 'db.manager' LIMIT 1);
SET @content_manager_id = (SELECT id FROM jaktfelt_users WHERE username = 'content.manager' LIMIT 1);
SET @role_manager_id = (SELECT id FROM jaktfelt_users WHERE username = 'role.manager' LIMIT 1);

-- Get role IDs
SET @databasemanager_role_id = (SELECT id FROM jaktfelt_roles WHERE role_name = 'databasemanager' LIMIT 1);
SET @contentmanager_role_id = (SELECT id FROM jaktfelt_roles WHERE role_name = 'contentmanager' LIMIT 1);
SET @rolemanager_role_id = (SELECT id FROM jaktfelt_roles WHERE role_name = 'rolemanager' LIMIT 1);

-- Assign roles to users
INSERT INTO jaktfelt_user_roles (user_id, role_id, is_test_data) VALUES
(@db_manager_id, @databasemanager_role_id, 1),
(@content_manager_id, @contentmanager_role_id, 1),
(@role_manager_id, @rolemanager_role_id, 1);

-- Verify the setup
SELECT 
    u.username,
    u.first_name,
    u.last_name,
    u.email,
    r.role_name,
    'Password: password123' as login_info
FROM jaktfelt_users u
JOIN jaktfelt_user_roles ur ON u.id = ur.user_id
JOIN jaktfelt_roles r ON ur.role_id = r.id
WHERE u.username IN ('db.manager', 'content.manager', 'role.manager')
ORDER BY u.username;
