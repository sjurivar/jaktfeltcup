# User Management Scripts

This directory contains scripts for managing users and passwords in the Jaktfeltcup application.

## 🔧 Password Fix Scripts

### 1. `fix_user_passwords.php`
Updates existing users with correct password hashes.

**Usage:**
```bash
php scripts/setup/fix_user_passwords.php
```

**What it does:**
- Updates passwords for existing users
- Generates correct PHP password_hash() hashes
- Tests password verification
- Shows login information

### 2. `create_working_users.php`
Creates new users with correct password hashes.

**Usage:**
```bash
php scripts/setup/create_working_users.php
```

**What it does:**
- Creates 5 test users with different roles
- Generates correct password hashes
- Assigns roles to users
- Tests password verification

### 3. `create_admin_quick.php`
Creates a single admin user quickly.

**Usage:**
```bash
php scripts/setup/create_admin_quick.php
```

**What it does:**
- Creates one admin user
- Assigns admin role
- Tests password verification
- Shows login information

## 🧪 Testing Scripts

### 4. `test_login.php`
Tests the login functionality with all users.

**Usage:**
```bash
php scripts/setup/test_login.php
```

**What it does:**
- Tests password verification for all users
- Tests full login process
- Shows user roles
- Identifies login issues

### 5. `reset_users.php`
Removes all users from the database.

**Usage:**
```bash
php scripts/setup/reset_users.php
```

**What it does:**
- Deletes all users and user roles
- Allows fresh start
- Requires confirmation

## 🎯 Default Users Created

| Username | Email | Password | Role |
|----------|-------|----------|------|
| admin | admin@jaktfeltcup.no | admin123 | admin |
| db.manager | db.manager@jaktfeltcup.no | db123 | databasemanager |
| content.manager | content.manager@jaktfeltcup.no | content123 | contentmanager |
| role.manager | role.manager@jaktfeltcup.no | role123 | rolemanager |
| user1 | user1@jaktfeltcup.no | user123 | user |

## 🚀 Quick Start

1. **Reset everything:**
   ```bash
   php scripts/setup/reset_users.php
   ```

2. **Create working users:**
   ```bash
   php scripts/setup/create_working_users.php
   ```

3. **Test login:**
   ```bash
   php scripts/setup/test_login.php
   ```

4. **Login to admin panel:**
   - Go to: `http://localhost/jaktfeltcup/login`
   - Username: `admin`
   - Password: `admin123`

## 🔐 Password Hashing

The application uses PHP's `password_hash()` function with `PASSWORD_DEFAULT` algorithm (bcrypt).

**Important:**
- MySQL's `SHA2()`, `MD5()`, and `SHA1()` functions are NOT compatible
- Only use PHP `password_hash()` for password hashing
- Use `password_verify()` for password verification

## 🛠️ Troubleshooting

### Login Issues
1. Run `test_login.php` to identify problems
2. Check if users exist in database
3. Verify password hashes are correct
4. Check if roles are assigned

### Password Hash Issues
1. Use `fix_user_passwords.php` to update hashes
2. Ensure using PHP `password_hash()` not MySQL functions
3. Test with `test_login.php`

### Role Issues
1. Check if roles exist in `jaktfelt_roles` table
2. Verify user-role assignments in `jaktfelt_user_roles` table
3. Run role setup script if needed

## 📝 Notes

- All scripts include error handling and detailed output
- Scripts are safe to run multiple times
- Test users are marked with `is_test_data = 1`
- Scripts work with the existing database structure
