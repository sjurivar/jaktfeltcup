-- MySQL User Creation with Different Hash Functions
-- Demonstrates various MySQL password hashing methods
-- 
-- Usage: Run this script in MySQL to create users with different hash types

-- ===========================================
-- USER DATA - MODIFY THESE VALUES
-- ===========================================

-- User 1: SHA-256 (Recommended)
SET @username1 = 'admin_sha256';
SET @first_name1 = 'Admin';
SET @last_name1 = 'SHA256';
SET @email1 = 'admin_sha256@jaktfeltcup.no';
SET @password1 = 'password123';
SET @role1 = 'admin';

-- User 2: SHA-512 (Most Secure)
SET @username2 = 'admin_sha512';
SET @first_name2 = 'Admin';
SET @last_name2 = 'SHA512';
SET @email2 = 'admin_sha512@jaktfeltcup.no';
SET @password2 = 'password123';
SET @role2 = 'admin';

-- User 3: MD5 (Not Recommended)
SET @username3 = 'admin_md5';
SET @first_name3 = 'Admin';
SET @last_name3 = 'MD5';
SET @email3 = 'admin_md5@jaktfeltcup.no';
SET @password3 = 'password123';
SET @role3 = 'admin';

-- User 4: SHA-1 (Not Recommended)
SET @username4 = 'admin_sha1';
SET @first_name4 = 'Admin';
SET @last_name4 = 'SHA1';
SET @email4 = 'admin_sha1@jaktfeltcup.no';
SET @password4 = 'password123';
SET @role4 = 'admin';

-- ===========================================
-- CREATE USERS WITH DIFFERENT HASH METHODS
-- ===========================================

-- Insert users with different hash methods
INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at, is_test_data) VALUES
-- SHA-256 (Recommended)
(@username1, @first_name1, @last_name1, @email1, SHA2(@password1, 256), 1, NOW(), 1),
-- SHA-512 (Most Secure)
(@username2, @first_name2, @last_name2, @email2, SHA2(@password2, 512), 1, NOW(), 1),
-- MD5 (Not Recommended)
(@username3, @first_name3, @last_name3, @email3, MD5(@password3), 1, NOW(), 1),
-- SHA-1 (Not Recommended)
(@username4, @first_name4, @last_name4, @email4, SHA1(@password4), 1, NOW(), 1);

-- ===========================================
-- GET USER IDs
-- ===========================================

SET @user1_id = (SELECT id FROM jaktfelt_users WHERE username = @username1 LIMIT 1);
SET @user2_id = (SELECT id FROM jaktfelt_users WHERE username = @username2 LIMIT 1);
SET @user3_id = (SELECT id FROM jaktfelt_users WHERE username = @username3 LIMIT 1);
SET @user4_id = (SELECT id FROM jaktfelt_users WHERE username = @username4 LIMIT 1);

-- ===========================================
-- GET ROLE IDs
-- ===========================================

SET @admin_role_id = (SELECT id FROM jaktfelt_roles WHERE role_name = @role1 LIMIT 1);

-- ===========================================
-- ASSIGN ROLES TO USERS
-- ===========================================

INSERT INTO jaktfelt_user_roles (user_id, role_id, is_test_data) VALUES
(@user1_id, @admin_role_id, 1),
(@user2_id, @admin_role_id, 1),
(@user3_id, @admin_role_id, 1),
(@user4_id, @admin_role_id, 1);

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
    LENGTH(u.password_hash) as hash_length,
    CASE 
        WHEN LENGTH(u.password_hash) = 64 THEN 'SHA-256'
        WHEN LENGTH(u.password_hash) = 128 THEN 'SHA-512'
        WHEN LENGTH(u.password_hash) = 32 THEN 'MD5'
        WHEN LENGTH(u.password_hash) = 40 THEN 'SHA-1'
        ELSE 'Unknown'
    END as hash_type
FROM jaktfelt_users u
JOIN jaktfelt_user_roles ur ON u.id = ur.user_id
JOIN jaktfelt_roles r ON ur.role_id = r.id
WHERE u.username IN (@username1, @username2, @username3, @username4)
ORDER BY u.username;

-- ===========================================
-- HASH COMPARISON
-- ===========================================

SELECT 
    'password123' as original_password,
    SHA2('password123', 256) as sha256_hash,
    SHA2('password123', 512) as sha512_hash,
    MD5('password123') as md5_hash,
    SHA1('password123') as sha1_hash;

-- ===========================================
-- NOTES
-- ===========================================
-- 
-- ⚠️  IMPORTANT: This script is for demonstration only!
-- 
-- MySQL Hash Functions are NOT compatible with PHP password_verify():
-- 
-- 1. SHA2(password, 256) - SHA-256 (64 characters)
--    - NOT compatible with PHP password_verify()
--    - App will fail login verification
-- 
-- 2. SHA2(password, 512) - SHA-512 (128 characters)
--    - NOT compatible with PHP password_verify()
--    - App will fail login verification
-- 
-- 3. MD5(password) - MD5 (32 characters)
--    - NOT compatible with PHP password_verify()
--    - NOT RECOMMENDED for production
-- 
-- 4. SHA1(password) - SHA-1 (40 characters)
--    - NOT compatible with PHP password_verify()
--    - NOT RECOMMENDED for production
-- 
-- ✅ CORRECT APPROACH for this app:
-- - Use PHP password_hash() to generate bcrypt hashes
-- - Use PHP password_verify() to verify passwords
-- - MySQL hash functions will NOT work with this app
-- 
-- To generate compatible hashes, use:
-- php scripts/setup/generate_php_hashes.php
