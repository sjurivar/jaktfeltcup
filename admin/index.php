<?php
/**
 * Admin Dashboard
 * Main admin landing page with role-based access control
 */

session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

$page_title = 'Admin Dashboard';
$current_page = 'admin_dashboard';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('login'));
    exit;
}

// Get user roles
$user_roles = [];
try {
    $user_roles = $database->queryAll(
        "SELECT r.role_name FROM jaktfelt_user_roles ur 
         JOIN jaktfelt_roles r ON ur.role_id = r.id 
         WHERE ur.user_id = ?", 
        [$_SESSION['user_id']]
    );
    $user_roles = array_column($user_roles, 'role_name');
} catch (Exception $e) {
    // If roles table doesn't exist, assume no roles
    error_log("Could not fetch user roles: " . $e->getMessage());
}

// Check access permissions
$has_database_access = in_array('databasemanager', $user_roles) || in_array('admin', $user_roles);
$has_content_access = in_array('contentmanager', $user_roles) || in_array('admin', $user_roles);
$has_role_access = in_array('rolemanager', $user_roles) || in_array('admin', $user_roles);

// If user has no admin roles, redirect to home
if (empty($user_roles)) {
    $_SESSION['error'] = 'Du har ikke tilgang til admin-området.';
    header('Location: ' . base_url('/'));
    exit;
}

?>

<?php include_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h1>
            <p class="lead">Velkommen til Jaktfeltcup admin-området, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Bruker') ?>!</p>
        </div>
    </div>

    <!-- User Roles Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-user-shield me-2"></i>Dine tilganger</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($user_roles)): ?>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($user_roles as $role): ?>
                                <span class="badge bg-primary"><?= htmlspecialchars(ucfirst($role)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Ingen roller tildelt</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Modules -->
    <div class="row">
        <?php if ($has_database_access): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-database fa-4x text-primary mb-3"></i>
                    <h4 class="card-title">Database Management</h4>
                    <p class="card-text">
                        Administrer database, sjekk struktur, importer data og utfør vedlikehold.
                    </p>
                    <a href="<?= base_url('admin/database') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-database me-2"></i>Database Admin
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($has_content_access): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-edit fa-4x text-success mb-3"></i>
                    <h4 class="card-title">Content Management</h4>
                    <p class="card-text">
                        Rediger nyheter, administrer sponsorer og håndter innhold på nettsiden.
                    </p>
                    <a href="<?= base_url('admin/content') ?>" class="btn btn-success btn-lg">
                        <i class="fas fa-edit me-2"></i>Content Admin
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($has_role_access): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users-cog fa-4x text-warning mb-3"></i>
                    <h4 class="card-title">User & Role Management</h4>
                    <p class="card-text">
                        Administrer brukere, tildel roller og håndter tilgangsrettigheter.
                    </p>
                    <a href="<?= base_url('admin/roles') ?>" class="btn btn-warning btn-lg">
                        <i class="fas fa-users-cog me-2"></i>User & Roles
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar me-2"></i>System Oversikt</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        try {
                            $user_count = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_users")['count'];
                            $news_count = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_news")['count'];
                            $sponsor_count = $database->queryOne("SELECT COUNT(*) as count FROM jaktfelt_sponsors")['count'];
                        } catch (Exception $e) {
                            $user_count = $news_count = $sponsor_count = 0;
                        }
                        ?>
                        <div class="col-md-3 text-center">
                            <h3 class="text-primary"><?= $user_count ?></h3>
                            <p class="text-muted">Brukere</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-success"><?= $news_count ?></h3>
                            <p class="text-muted">Nyheter</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-warning"><?= $sponsor_count ?></h3>
                            <p class="text-muted">Sponsorer</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-info"><?= count($user_roles) ?></h3>
                            <p class="text-muted">Dine roller</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_footer(); ?>
