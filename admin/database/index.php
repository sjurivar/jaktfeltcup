<?php
/**
 * Database Admin Panel
 * Centralized management for database operations
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';

// Set page variables
$page_title = 'Database Admin';
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
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $dbConnected = true;
    $dbError = null;
} catch (PDOException $e) {
    $dbConnected = false;
    $dbError = $e->getMessage();
}
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-database me-2"></i>Database Admin Panel</h1>
            <p class="lead">Manage database structure, data, and operations</p>
        </div>
    </div>

    <?php if (!$dbConnected): ?>
    <div class="alert alert-danger">
        <h4><i class="fas fa-exclamation-triangle me-2"></i>Database Connection Failed</h4>
        <p><strong>Error:</strong> <?= htmlspecialchars($dbError) ?></p>
        <p>Please check your database configuration in <code>config/config.php</code></p>
    </div>
    <?php else: ?>
    
    <!-- Database Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>Database Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Database:</strong> <?= htmlspecialchars($dbname) ?></p>
                            <p><strong>Host:</strong> <?= htmlspecialchars($host) ?></p>
                            <p><strong>User:</strong> <?= htmlspecialchars($user) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> <span class="badge bg-success">Connected</span></p>
                            <p><strong>Time:</strong> <?= date('Y-m-d H:i:s') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="check_tables.php" class="btn btn-info w-100">
                                <i class="fas fa-list me-2"></i>Check Tables
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="check_data.php" class="btn btn-info w-100">
                                <i class="fas fa-chart-bar me-2"></i>Check Data
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="check_structure.php" class="btn btn-info w-100">
                                <i class="fas fa-cogs me-2"></i>Check Structure
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="check_constraints.php" class="btn btn-info w-100">
                                <i class="fas fa-link me-2"></i>Check Constraints
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="check_columns.php" class="btn btn-info w-100">
                                <i class="fas fa-columns me-2"></i>Check Columns
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts Directory -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-folder me-2"></i>Scripts Directory</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6><i class="fas fa-tools me-2"></i>Setup Scripts</h6>
                            <a href="../../scripts/setup/setup_database.php" class="btn btn-primary btn-sm me-2">
                                <i class="fas fa-database me-1"></i>Setup Database
                            </a>
                            <a href="../../scripts/setup/setup_sample_data.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-upload me-1"></i>Setup Sample Data
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6><i class="fas fa-sync me-2"></i>Migration Scripts</h6>
                            <a href="../../scripts/migration/migrate_add_test_data_column.php" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-plus me-1"></i>Add Test Data Column
                            </a>
                            <a href="../../scripts/migration/check_database_structure.php" class="btn btn-info btn-sm">
                                <i class="fas fa-search me-1"></i>Check Structure
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6><i class="fas fa-bug me-2"></i>Debug Scripts</h6>
                            <a href="../../scripts/debug/check_database_tables.php" class="btn btn-secondary btn-sm me-2">
                                <i class="fas fa-table me-1"></i>Check Tables
                            </a>
                            <a href="../../scripts/debug/check_imported_data.php" class="btn btn-secondary btn-sm">
                                <i class="fas fa-chart-bar me-1"></i>Check Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Operations -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5><i class="fas fa-trash me-2"></i>Dangerous Operations</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">These operations will permanently delete data!</p>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-danger" onclick="confirmAction('drop_old_tables.php', 'Drop Old Tables', 'This will delete all old tables without jaktfelt_ prefix. Are you sure?')">
                            <i class="fas fa-trash me-2"></i>Drop Old Tables
                        </button>
                        
                        <button class="btn btn-outline-warning" onclick="confirmAction('drop_old_tables_safe.php', 'Drop Old Tables (Safe)', 'This will delete old tables in correct order to avoid foreign key issues. Are you sure?')">
                            <i class="fas fa-shield-alt me-2"></i>Drop Old Tables (Safe)
                        </button>
                        
                        <button class="btn btn-outline-danger" onclick="confirmAction('drop_new_tables.php', 'Drop New Tables', 'This will delete all tables with jaktfelt_ prefix. Are you sure?')">
                            <i class="fas fa-trash me-2"></i>Drop New Tables
                        </button>
                        
                        <button class="btn btn-outline-danger" onclick="confirmAction('drop_all_tables.php', 'Drop ALL Tables', 'This will delete ALL tables in the database. Are you sure?')">
                            <i class="fas fa-exclamation-triangle me-2"></i>Drop ALL Tables
                        </button>
                        
                        <button class="btn btn-outline-warning" onclick="confirmAction('clear_test_data.php', 'Clear Test Data', 'This will delete all records marked as test data. Are you sure?')">
                            <i class="fas fa-broom me-2"></i>Clear Test Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5><i class="fas fa-plus me-2"></i>Setup Operations</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">These operations will create or modify database structure and data.</p>
                    
                    <div class="d-grid gap-2">
                        <a href="setup_database.php" class="btn btn-success">
                            <i class="fas fa-database me-2"></i>Setup Database Schema
                        </a>
                        
                        <a href="setup_database_ordered.php" class="btn btn-outline-success">
                            <i class="fas fa-sort me-2"></i>Setup Database (Ordered)
                        </a>
                        
                        <a href="migrate_add_test_data_column.php" class="btn btn-primary">
                            <i class="fas fa-columns me-2"></i>Add Test Data Column
                        </a>
                        
                        <a href="import_sample_data.php" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Import Sample Data
                        </a>
                        
                        <a href="import_sample_data_fixed.php" class="btn btn-outline-primary">
                            <i class="fas fa-wrench me-2"></i>Import Sample Data (Fixed)
                        </a>
                        
                        <a href="import_results.php" class="btn btn-primary">
                            <i class="fas fa-trophy me-2"></i>Import Test Results
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Operations -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-history me-2"></i>Recent Operations</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">No recent operations logged yet.</p>
                    <small class="text-muted">This feature will be implemented to track database changes.</small>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>

<script>
function confirmAction(url, title, message) {
    if (confirm(message)) {
        if (confirm('This action cannot be undone. Are you absolutely sure?')) {
            window.location.href = url;
        }
    }
}
</script>

<?php include_footer(); ?>
