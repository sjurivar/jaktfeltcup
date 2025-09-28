<?php
/**
 * User & Role Management
 * Manage users and their roles
 */

session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

$page_title = 'User & Role Management';
$current_page = 'admin_roles';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('login'));
    exit;
}

// Check if user has role management access
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
    error_log("Could not fetch user roles: " . $e->getMessage());
}

if (!in_array('rolemanager', $user_roles) && !in_array('admin', $user_roles)) {
    $_SESSION['error'] = 'Du har ikke tilgang til rollestyring.';
    header('Location: ' . base_url('admin'));
    exit;
}

// Handle form submissions
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Get users with their roles
$users = [];
$roles = [];

try {
    $users = $database->queryAll(
        "SELECT u.id, u.first_name, u.last_name, u.email, u.created_at,
                GROUP_CONCAT(r.role_name) as roles
         FROM jaktfelt_users u
         LEFT JOIN jaktfelt_user_roles ur ON u.id = ur.user_id
         LEFT JOIN jaktfelt_roles r ON ur.role_id = r.id
         GROUP BY u.id
         ORDER BY u.last_name, u.first_name"
    );
    
    $roles = $database->queryAll("SELECT * FROM jaktfelt_roles ORDER BY role_name");
} catch (Exception $e) {
    error_log("Could not fetch users/roles: " . $e->getMessage());
}

?>

<?php include_header(); ?>

<div class="container mt-4">
    <h1 class="mb-4"><i class="fas fa-users-cog me-2"></i>User & Role Management</h1>
    <p class="lead">Administrer brukere og deres roller i systemet.</p>

    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= htmlspecialchars($success_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($error_message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Users List -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-users me-2"></i>Brukere</h5>
                    <span class="badge bg-primary"><?= count($users) ?> brukere</span>
                </div>
                <div class="card-body">
                    <?php if (!empty($users)): ?>
                        <div class="list-group">
                            <?php foreach ($users as $user): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h6>
                                            <p class="mb-1 text-muted small"><?= htmlspecialchars($user['email']) ?></p>
                                            <small class="text-muted">
                                                Registrert: <?= date('d.m.Y', strtotime($user['created_at'])) ?>
                                            </small>
                                            <div class="mt-2">
                                                <?php if (!empty($user['roles'])): ?>
                                                    <?php foreach (explode(',', $user['roles']) as $role): ?>
                                                        <span class="badge bg-secondary me-1"><?= htmlspecialchars(ucfirst($role)) ?></span>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-dark">Ingen roller</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary btn-sm" onclick="editUserRoles(<?= $user['id'] ?>, '<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>', '<?= htmlspecialchars($user['roles'] ?? '') ?>')">
                                                <i class="fas fa-user-cog"></i> Roller
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>Ingen brukere registrert ennå.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Roles Info -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-shield-alt me-2"></i>System Roller</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <div class="mb-3">
                                <h6 class="mb-1"><?= htmlspecialchars(ucfirst($role['role_name'])) ?></h6>
                                <p class="text-muted small mb-0"><?= htmlspecialchars($role['description'] ?? 'Ingen beskrivelse') ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Ingen roller definert.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Roles Modal -->
<div class="modal fade" id="editRolesModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rediger brukerroller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRolesForm" method="POST" action="handlers/update_roles.php">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <h6 id="edit_user_name"></h6>
                    <p class="text-muted">Velg roller for denne brukeren:</p>
                    
                    <?php if (!empty($roles)): ?>
                        <?php foreach ($roles as $role): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="<?= $role['id'] ?>" id="role_<?= $role['id'] ?>">
                                <label class="form-check-label" for="role_<?= $role['id'] ?>">
                                    <?= htmlspecialchars(ucfirst($role['role_name'])) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                    <button type="submit" class="btn btn-primary">Lagre endringer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUserRoles(userId, userName, currentRoles) {
    document.getElementById('edit_user_id').value = userId;
    document.getElementById('edit_user_name').textContent = userName;
    
    // Clear all checkboxes
    document.querySelectorAll('input[name="roles[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Check current roles
    if (currentRoles) {
        const roleNames = currentRoles.split(',');
        document.querySelectorAll('input[name="roles[]"]').forEach(checkbox => {
            const label = checkbox.nextElementSibling.textContent.toLowerCase();
            if (roleNames.some(role => label.includes(role.toLowerCase()))) {
                checkbox.checked = true;
            }
        });
    }
    
    new bootstrap.Modal(document.getElementById('editRolesModal')).show();
}
</script>

<?php include_footer(); ?>
