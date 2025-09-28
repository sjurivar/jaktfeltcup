<?php
/**
 * Web-based First Admin Setup
 * Creates the first admin user via web interface
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../src/Core/Database.php';

$page_title = 'Setup First Admin';
$current_page = 'setup_admin';

// Check if setup is needed
$setup_needed = false;
$error_message = '';
$success_message = '';

try {
    // Initialize database connection
    global $db_config;
    $database = new \Jaktfeltcup\Core\Database($db_config);
    
    // Check if any users exist
    $user_count = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_users")['count'];
    
    if ($user_count == 0) {
        $setup_needed = true;
    } else {
        // Check if any admin users exist
        $admin_count = $database->queryOne(
            "SELECT COUNT(*) as count FROM jaktfelt_user_roles ur 
             JOIN jaktfelt_roles r ON ur.role_id = r.id 
             WHERE r.role_name = 'admin'"
        )['count'];
        
        if ($admin_count == 0) {
            $setup_needed = true;
            $error_message = "No admin users found. You need to create an admin user or assign admin role to existing users.";
        }
    }
} catch (Exception $e) {
    $setup_needed = true;
    $error_message = "Database connection failed: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $setup_needed) {
    try {
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            throw new Exception("All fields are required!");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format!");
        }
        
        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match!");
        }
        
        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters long!");
        }
        
        // Check if email already exists
        $existing_user = $database->queryOne("SELECT id FROM jaktfelt_users WHERE email = ?", [$email]);
        if ($existing_user) {
            throw new Exception("User with this email already exists!");
        }
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Create user
        $username = strtolower($first_name . '.' . $last_name);
        $user_id = $database->execute(
            "INSERT INTO jaktfelt_users (username, first_name, last_name, email, password_hash, email_verified, created_at) 
             VALUES (?, ?, ?, ?, ?, 1, NOW())",
            [$username, $first_name, $last_name, $email, $hashed_password]
        );
        
        // Ensure roles table exists
        try {
            $admin_role = $database->queryOne("SELECT id FROM jaktfelt_roles WHERE role_name = 'admin'");
            if (!$admin_role) {
                // Create roles table
                $roles_sql = file_get_contents(__DIR__ . '/../../database/roles_tables.sql');
                $statements = array_filter(array_map('trim', explode(';', $roles_sql)));
                
                foreach ($statements as $statement) {
                    if (!empty($statement) && !preg_match('/^--/', $statement)) {
                        try {
                            $database->execute($statement);
                        } catch (Exception $e) {
                            // Ignore "table already exists" errors
                            if (strpos($e->getMessage(), 'already exists') === false) {
                                throw $e;
                            }
                        }
                    }
                }
            }
            
            // Get admin role ID
            $admin_role = $database->queryOne("SELECT id FROM jaktfelt_roles WHERE role_name = 'admin'");
            
            if ($admin_role) {
                // Assign admin role to user
                $database->execute(
                    "INSERT INTO jaktfelt_user_roles (user_id, role_id) VALUES (?, ?)",
                    [$user_id, $admin_role['id']]
                );
            }
            
        } catch (Exception $e) {
            // Roles setup failed, but user was created
            error_log("Could not set up roles: " . $e->getMessage());
        }
        
        $success_message = "Admin user created successfully! You can now log in.";
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

?>

<?php include_header(); ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-shield me-2"></i>Setup First Admin</h4>
                </div>
                <div class="card-body">
                    <?php if (!$setup_needed): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Setup Complete!</strong> Admin users already exist in the system.
                        </div>
                        <div class="text-center">
                            <a href="<?= base_url('login') ?>" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Go to Login
                            </a>
                            <a href="<?= base_url('admin') ?>" class="btn btn-success">
                                <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                            </a>
                        </div>
                    <?php else: ?>
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= htmlspecialchars($error_message) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success_message): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= htmlspecialchars($success_message) ?>
                            </div>
                            <div class="text-center">
                                <a href="<?= base_url('login') ?>" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Go to Login
                                </a>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Create the first admin user to access the system.</p>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i>Create Admin User
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php include_footer(); ?>
