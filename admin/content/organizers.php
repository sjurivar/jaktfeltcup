<?php
/**
 * Organizer Management Page
 * Only accessible by contentmanager and databasemanager
 */

// Start session
session_start();

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../src/Helpers/OrganizerHelper.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('login'));
    exit;
}

// Check if user has required role
$userRoles = $_SESSION['roles'] ?? [];
$hasAccess = false;

foreach ($userRoles as $role) {
    if (in_array($role['role_name'], ['contentmanager', 'databasemanager'])) {
        $hasAccess = true;
        break;
    }
}

if (!$hasAccess) {
    $_SESSION['error'] = 'Du har ikke tilgang til denne siden';
    header('Location: ' . base_url('admin'));
    exit;
}

// Set page variables
$page_title = 'Administrer Arrangører - Admin';
$current_page = 'admin';

// Get all organizers
$organizers = \Jaktfeltcup\Helpers\OrganizerHelper::getAllOrganizers();

include_header();
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-users me-2"></i>Administrer Arrangører
                </h1>
                <div>
                    <a href="<?= base_url('admin/content') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Tilbake
                    </a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrganizerModal">
                        <i class="fas fa-plus me-2"></i>Ny arrangør
                    </button>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Organizers Table -->
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($organizers)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Navn</th>
                                        <th>Type</th>
                                        <th>Kontaktperson</th>
                                        <th>E-post</th>
                                        <th>Telefon</th>
                                        <th>Sted</th>
                                        <th>Handlinger</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($organizers as $organizer): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($organizer['name']) ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= \Jaktfeltcup\Helpers\OrganizerHelper::formatOrganizationType($organizer['organization_type']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($organizer['contact_person'] ?? '-') ?></td>
                                            <td>
                                                <?php if ($organizer['email']): ?>
                                                    <a href="mailto:<?= htmlspecialchars($organizer['email']) ?>">
                                                        <?= htmlspecialchars($organizer['email']) ?>
                                                    </a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($organizer['phone'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($organizer['city'] ?? '-') ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary edit-organizer-btn" 
                                                        data-id="<?= $organizer['id'] ?>"
                                                        data-name="<?= htmlspecialchars($organizer['name']) ?>"
                                                        data-type="<?= htmlspecialchars($organizer['organization_type']) ?>"
                                                        data-contact="<?= htmlspecialchars($organizer['contact_person'] ?? '') ?>"
                                                        data-email="<?= htmlspecialchars($organizer['email'] ?? '') ?>"
                                                        data-phone="<?= htmlspecialchars($organizer['phone'] ?? '') ?>"
                                                        data-city="<?= htmlspecialchars($organizer['city'] ?? '') ?>"
                                                        data-description="<?= htmlspecialchars($organizer['description'] ?? '') ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-organizer-btn" 
                                                        data-id="<?= $organizer['id'] ?>"
                                                        data-name="<?= htmlspecialchars($organizer['name']) ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>Ingen arrangører registrert ennå.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrganizerModal">
                                <i class="fas fa-plus me-2"></i>Legg til første arrangør
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Organizer Modal -->
<div class="modal fade" id="addOrganizerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Ny arrangør
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/content/handlers/organizer.php') ?>" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Navn *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type *</label>
                            <select class="form-select" name="organization_type" required>
                                <option value="skytterlag">Skytterlag</option>
                                <option value="njff_lokallag">NJFF Lokallag</option>
                                <option value="dfs_lokallag">DFS Lokallag</option>
                                <option value="annet">Annet</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kontaktperson</label>
                            <input type="text" class="form-control" name="contact_person">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">E-post</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefon</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sted/By</label>
                            <input type="text" class="form-control" name="city">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Beskrivelse</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lagre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Organizer Modal -->
<div class="modal fade" id="editOrganizerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Rediger arrangør
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/content/handlers/organizer.php') ?>" method="POST">
                <input type="hidden" name="organizer_id" id="edit_organizer_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Navn *</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type *</label>
                            <select class="form-select" name="organization_type" id="edit_type" required>
                                <option value="skytterlag">Skytterlag</option>
                                <option value="njff_lokallag">NJFF Lokallag</option>
                                <option value="dfs_lokallag">DFS Lokallag</option>
                                <option value="annet">Annet</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kontaktperson</label>
                            <input type="text" class="form-control" name="contact_person" id="edit_contact">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">E-post</label>
                            <input type="email" class="form-control" name="email" id="edit_email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefon</label>
                            <input type="tel" class="form-control" name="phone" id="edit_phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sted/By</label>
                            <input type="text" class="form-control" name="city" id="edit_city">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Beskrivelse</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lagre endringer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Edit organizer
document.querySelectorAll('.edit-organizer-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('edit_organizer_id').value = this.dataset.id;
        document.getElementById('edit_name').value = this.dataset.name;
        document.getElementById('edit_type').value = this.dataset.type;
        document.getElementById('edit_contact').value = this.dataset.contact;
        document.getElementById('edit_email').value = this.dataset.email;
        document.getElementById('edit_phone').value = this.dataset.phone;
        document.getElementById('edit_city').value = this.dataset.city;
        document.getElementById('edit_description').value = this.dataset.description;
        
        new bootstrap.Modal(document.getElementById('editOrganizerModal')).show();
    });
});

// Delete organizer
document.querySelectorAll('.delete-organizer-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('Er du sikker på at du vil slette ' + this.dataset.name + '?')) {
            window.location.href = '<?= base_url('admin/content/handlers/organizer.php') ?>?delete=' + this.dataset.id;
        }
    });
});
</script>

<?php include_footer(); ?>

