<?php
/**
 * Content Management System
 * Simple and safe content editing for Jaktfeltcup
 */

session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
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
    // If roles table doesn't exist, assume no roles
    error_log("Could not fetch user roles: " . $e->getMessage());
}

if (!in_array('contentmanager', $user_roles) && !in_array('admin', $user_roles)) {
    $_SESSION['error'] = 'Du har ikke tilgang til innholdsredigering.';
    header('Location: ' . base_url('admin'));
    exit;
}

$page_title = 'Innholdsredigering';
$current_page = 'admin_content';

// Handle form submissions
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

// Get current content
$news_items = [];
$sponsors = [];

try {
    // Get news items
    $news_items = $database->queryAll("SELECT * FROM jaktfelt_news ORDER BY created_at DESC LIMIT 10");
} catch (Exception $e) {
    error_log("Could not fetch news: " . $e->getMessage());
}

try {
    // Get sponsors
    $sponsors = $database->queryAll("SELECT * FROM jaktfelt_sponsors ORDER BY sponsor_level DESC, name ASC");
} catch (Exception $e) {
    error_log("Could not fetch sponsors: " . $e->getMessage());
}

?>

<?php include_header(); ?>

<div class="container mt-4">
    <h1 class="mb-4"><i class="fas fa-edit me-2"></i>Innholdsredigering</h1>
    <p class="lead">Enkel og trygg redigering av innhold på Jaktfeltcup.</p>

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
        <!-- News Management -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-newspaper me-2"></i>Nyheter</h5>
                    <div>
                        <a href="<?= base_url('admin/content/text') ?>" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Tekstinnhold
                        </a>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newsModal">
                            <i class="fas fa-plus me-1"></i>Ny nyhet
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($news_items)): ?>
                        <div class="list-group">
                            <?php foreach ($news_items as $news): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex align-items-start">
                                            <?php if (!empty($news['image_url'])): ?>
                                                <div class="me-3">
                                                    <img src="<?= htmlspecialchars($news['image_url']) ?>" 
                                                         alt="<?= htmlspecialchars($news['title']) ?>" 
                                                         class="img-thumbnail" 
                                                         style="width: 60px; height: 60px; object-fit: cover;"
                                                         onerror="this.style.display='none'">
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($news['title']) ?></h6>
                                                <p class="mb-1 text-muted small"><?= htmlspecialchars($news['excerpt']) ?></p>
                                                <small class="text-muted">
                                                    <?= date('d.m.Y H:i', strtotime($news['created_at'])) ?>
                                                </small>
                                                <?php if (!empty($news['image_url'])): ?>
                                                    <br><small class="text-muted">Bilde: <?= htmlspecialchars(basename($news['image_url'])) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary btn-sm" onclick="editNews(<?= htmlspecialchars(json_encode($news)) ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="deleteNews(<?= $news['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-newspaper fa-3x mb-3"></i>
                            <p>Ingen nyheter registrert ennå.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newsModal">
                                <i class="fas fa-plus me-1"></i>Legg til første nyhet
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sponsor Management -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-handshake me-2"></i>Sponsorer</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#sponsorModal">
                        <i class="fas fa-plus me-1"></i>Ny sponsor
                    </button>
                </div>
                <div class="card-body">
                    <?php if (!empty($sponsors)): ?>
                        <div class="list-group">
                            <?php foreach ($sponsors as $sponsor): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex align-items-start">
                                            <?php if (!empty($sponsor['logo_url'])): ?>
                                                <div class="me-3">
                                                    <img src="<?= htmlspecialchars($sponsor['logo_url']) ?>" 
                                                         alt="<?= htmlspecialchars($sponsor['name']) ?>" 
                                                         class="img-thumbnail" 
                                                         style="width: 60px; height: 60px; object-fit: contain;"
                                                         onerror="this.style.display='none'">
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($sponsor['name']) ?></h6>
                                                <p class="mb-1 text-muted small"><?= htmlspecialchars($sponsor['description']) ?></p>
                                                <span class="badge bg-<?= $sponsor['sponsor_level'] === 'gold' ? 'warning' : ($sponsor['sponsor_level'] === 'silver' ? 'secondary' : 'success') ?>">
                                                    <?= ucfirst($sponsor['sponsor_level']) ?>
                                                </span>
                                                <?php if (!empty($sponsor['logo_url'])): ?>
                                                    <br><small class="text-muted">Logo: <?= htmlspecialchars(basename($sponsor['logo_url'])) ?></small>
                                                <?php else: ?>
                                                    <br><small class="text-warning">Ingen logo</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary btn-sm" onclick="editSponsor(<?= htmlspecialchars(json_encode($sponsor)) ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="deleteSponsor(<?= $sponsor['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-handshake fa-3x mb-3"></i>
                            <p>Ingen sponsorer registrert ennå.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sponsorModal">
                                <i class="fas fa-plus me-1"></i>Legg til første sponsor
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- News Modal -->
<div class="modal fade" id="newsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nyhet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="newsForm" method="POST" action="handlers/news.php">
                <div class="modal-body">
                    <input type="hidden" name="news_id" id="news_id">
                    <div class="mb-3">
                        <label for="news_title" class="form-label">Tittel</label>
                        <input type="text" class="form-control" id="news_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="news_excerpt" class="form-label">Kort beskrivelse</label>
                        <textarea class="form-control" id="news_excerpt" name="excerpt" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="news_content" class="form-label">Innhold</label>
                        <textarea class="form-control" id="news_content" name="content" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="news_image" class="form-label">Bilde URL (valgfri)</label>
                        <input type="url" class="form-control" id="news_image" name="image_url">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                    <button type="submit" class="btn btn-primary">Lagre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sponsor Modal -->
<div class="modal fade" id="sponsorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sponsor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="sponsorForm" method="POST" action="handlers/sponsor.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="sponsor_id" id="sponsor_id">
                    <div class="mb-3">
                        <label for="sponsor_name" class="form-label">Navn</label>
                        <input type="text" class="form-control" id="sponsor_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="sponsor_description" class="form-label">Beskrivelse</label>
                        <textarea class="form-control" id="sponsor_description" name="description" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="sponsor_level" class="form-label">Sponsor-nivå</label>
                        <select class="form-select" id="sponsor_level" name="sponsor_level" required>
                            <option value="bronze">Bronze</option>
                            <option value="silver">Sølv</option>
                            <option value="gold">Gull</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sponsor_website" class="form-label">Nettside URL (valgfri)</label>
                        <input type="url" class="form-control" id="sponsor_website" name="website_url">
                    </div>
                    <div class="mb-3">
                        <label for="sponsor_logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" id="sponsor_logo" name="logo" accept="image/*">
                        <div class="form-text">Støtter PNG, JPG, JPEG, GIF, SVG. Maksimal størrelse: 2MB</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Avbryt</button>
                    <button type="submit" class="btn btn-primary">Lagre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editNews(news) {
    document.getElementById('news_id').value = news.id;
    document.getElementById('news_title').value = news.title;
    document.getElementById('news_excerpt').value = news.excerpt;
    document.getElementById('news_content').value = news.content;
    document.getElementById('news_image').value = news.image_url || '';
    new bootstrap.Modal(document.getElementById('newsModal')).show();
}

function editSponsor(sponsor) {
    document.getElementById('sponsor_id').value = sponsor.id;
    document.getElementById('sponsor_name').value = sponsor.name;
    document.getElementById('sponsor_description').value = sponsor.description;
    document.getElementById('sponsor_level').value = sponsor.sponsor_level;
    document.getElementById('sponsor_website').value = sponsor.website_url || '';
    new bootstrap.Modal(document.getElementById('sponsorModal')).show();
}

function deleteNews(id) {
    if (confirm('Er du sikker på at du vil slette denne nyheten?')) {
        fetch('handlers/news.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=delete&id=' + id
        }).then(() => location.reload());
    }
}

function deleteSponsor(id) {
    if (confirm('Er du sikker på at du vil slette denne sponsoren?')) {
        fetch('handlers/sponsor.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'action=delete&id=' + id
        }).then(() => location.reload());
    }
}
</script>

<?php include_footer(); ?>
