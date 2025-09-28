<?php
/**
 * Text Content Management
 * Edit text content on pages
 */

session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../src/Helpers/ContentHelper.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('login'));
    exit;
}

// Check if user has content management access
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

if (!in_array('contentmanager', $user_roles) && !in_array('admin', $user_roles)) {
    $_SESSION['error'] = 'Du har ikke tilgang til innholdsredigering.';
    header('Location: ' . base_url('admin'));
    exit;
}

$page_title = 'Tekstinnhold';
$current_page = 'admin_content_text';

// Handle form submissions
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Get current page content
$pages = [
    'arrangor' => 'Arrangør',
    'sponsor' => 'Sponsor', 
    'deltaker' => 'Deltaker',
    'publikum' => 'Publikum',
    'general' => 'Generelt'
];

$current_page_key = $_GET['page'] ?? 'arrangor';
$page_content = [];

try {
    $page_content = get_page_all_content($current_page_key);
} catch (Exception $e) {
    $error_message = "Kunne ikke hente innhold: " . $e->getMessage();
}

?>

<?php include_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4"><i class="fas fa-edit me-2"></i>Tekstinnhold</h1>
            <p class="lead">Rediger tekstinnhold på sidene.</p>
        </div>
    </div>

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

    <!-- Page Selection -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-file-alt me-2"></i>Velg side</h5>
                </div>
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <?php foreach ($pages as $key => $name): ?>
                            <a href="?page=<?= $key ?>" 
                               class="btn <?= $current_page_key === $key ? 'btn-primary' : 'btn-outline-primary' ?>">
                                <?= htmlspecialchars($name) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Editor -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-edit me-2"></i><?= htmlspecialchars($pages[$current_page_key] ?? 'Ukjent side') ?></h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($page_content)): ?>
                        <form method="POST" action="handlers/update_text.php">
                            <input type="hidden" name="page_key" value="<?= htmlspecialchars($current_page_key) ?>">
                            
                            <?php foreach ($page_content as $section_key => $content): ?>
                                <div class="mb-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="title_<?= $section_key ?>" class="form-label">
                                                Overskrift (<?= htmlspecialchars($section_key) ?>)
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="title_<?= $section_key ?>" 
                                                   name="content[<?= $section_key ?>][title]" 
                                                   value="<?= htmlspecialchars($content['title']) ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="content_<?= $section_key ?>" class="form-label">
                                                Innhold
                                            </label>
                                            <textarea class="form-control" 
                                                      id="content_<?= $section_key ?>" 
                                                      name="content[<?= $section_key ?>][content]" 
                                                      rows="3"><?= htmlspecialchars($content['content']) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Lagre endringer
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-file-alt fa-3x mb-3"></i>
                            <p>Ingen tekstinnhold funnet for denne siden.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_footer(); ?>
