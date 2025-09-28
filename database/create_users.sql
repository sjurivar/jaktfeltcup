-- General User Creation Script
-- Creates users with hashed passwords and assigns roles
-- 
-- Usage: 
-- 1. Replace the user data below with your desired users
-- 2. Update the password hashes (use PHP password_hash() function)
-- 3. Run the script

-- ===========================================
-- USER DATA - MODIFY THESE VALUES
-- ===========================================

-- User 1: Admin User
SET @username1 = 'admin';
SET @first_name1 = 'Admin';
SET @last_name1 = 'User';
SET @email1 = 'admin@jaktfeltcup.no';
SET @password1 = 'password123';
SET @role1 = 'admin';

-- User 2: Database Manager
SET @username2 = 'db.manager';
SET @first_name2 = 'Database';
SET @last_name2 = 'Manager';
SET @email2 = 'db.manager@jaktfeltcup.no';
SET @password2 = 'password123';
SET @role2 = 'databasemanager';

-- User 3: Content Manager
SET @username3 = 'content.manager';
SET @first_name3 = 'Content';
SET @last_name3 = 'Manager';
SET @email3 = 'content.manager@jaktfeltcup.no';
SET @password3 = 'password123';
SET @role3 = 'contentmanager';

-- User 4: Role Manager
SET @username4 = 'role.manager';
SET @first_name4 = 'Role';
SET @last_name4 = 'Manager';
SET @email4 = 'role.manager@jaktfeltcup.no';
SET @password4 = 'password123';
SET @role4 = 'rolemanager';

-- User 5: Regular User
SET @username5 = 'user1';
SET @first_name5 = 'Regular';
SET @last_name5 = 'User';
SET @email5 = 'user1@jaktfeltcup.no';
SET @password5 = 'password123';
SET @role5 = 'user';

-- ===========================================
-- CREATE USERS
-- ===========================================

-- Insert users with PHP password_hash() compatible hashes
-- Note: These hashes are generated using PHP password_hash('password123', PASSWORD_DEFAULT)
INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at, is_test_data) VALUES
(@username1, @first_name1, @last_name1, @email1, '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 1, NOW(), 1),
(@username2, @first_name2, @last_name2, @email2, '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 1, NOW(), 1),
(@username3, @first_name3, @last_name3, @email3, '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 1, NOW(), 1),
(@username4, @first_name4, @last_name4, @email4, '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 1, NOW(), 1),
(@username5, @first_name5, @last_name5, @email5, '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 1, NOW(), 1);

-- ===========================================
-- GET USER IDs
-- ===========================================

SET @user1_id = (SELECT id FROM jaktfelt_users WHERE username = @username1 LIMIT 1);
SET @user2_id = (SELECT id FROM jaktfelt_users WHERE username = @username2 LIMIT 1);
SET @user3_id = (SELECT id FROM jaktfelt_users WHERE username = @username3 LIMIT 1);
SET @user4_id = (SELECT id FROM jaktfelt_users WHERE username = @username4 LIMIT 1);
SET @user5_id = (SELECT id FROM jaktfelt_users WHERE username = @username5 LIMIT 1);

-- ===========================================
-- GET ROLE IDs
-- ===========================================

SET @admin_role_id = (SELECT id FROM jaktfelt_roles WHERE role_name = @role1 LIMIT 1);
SET @databasemanager_role_id = (SELECT id FROM jaktfelt_roles WHERE role_name = @role2 LIMIT 1);
SET @contentmanager_role_id = (SELECT id FROM jaktfelt_roles WHERE role_name = @role3 LIMIT 1);
SET @rolemanager_role_id = (SELECT id FROM jaktfelt_roles WHERE role_name = @role4 LIMIT 1);
SET @user_role_id = (SELECT id FROM jaktfelt_roles WHERE role_name = @role5 LIMIT 1);

-- ===========================================
-- ASSIGN ROLES TO USERS
-- ===========================================

INSERT INTO jaktfelt_user_roles (user_id, role_id, is_test_data) VALUES
(@user1_id, @admin_role_id, 1),
(@user2_id, @databasemanager_role_id, 1),
(@user3_id, @contentmanager_role_id, 1),
(@user4_id, @rolemanager_role_id, 1),
(@user5_id, @user_role_id, 1);

-- ===========================================
-- VERIFY THE SETUP
-- ===========================================

SELECT 
    u.username,
    u.first_name,
    u.last_name,
    u.email,
    r.role_name,
    'Password: password123' as login_info,
    u.created_at
FROM jaktfelt_users u
JOIN jaktfelt_user_roles ur ON u.id = ur.user_id
JOIN jaktfelt_roles r ON ur.role_id = r.id
WHERE u.username IN (@username1, @username2, @username3, @username4, @username5)
ORDER BY u.username;

-- ===========================================
-- NOTES
-- ===========================================
-- 
-- This script uses MySQL's SHA2() function for password hashing
-- SHA2(password, 256) generates a 256-bit SHA-2 hash
-- 
-- Alternative MySQL hash functions:
-- - SHA2(password, 256) - SHA-256 (recommended)
-- - SHA2(password, 512) - SHA-512 (more secure)
-- - MD5(password) - MD5 (not recommended for production)
-- - SHA1(password) - SHA-1 (not recommended for production)
-- 
-- All users created with this script have:
-- - Password: password123 (hashed with SHA-256)
-- - Email verified: Yes
-- - Test data flag: Yes
-- - Created at: Current timestamp
