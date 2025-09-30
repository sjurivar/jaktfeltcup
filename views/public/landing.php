<?php
// Set page variables
$page_title = 'Nasjonal 15m Jaktfeltcup - Innendørs jaktfelt for alle';
$page_description = 'Delta i Nasjonal 15m Jaktfeltcup. Bli arrangør, sponsor eller deltaker i innendørs jaktfelt-konkurransen.';
$current_page = 'landing';

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../src/Core/Database.php';
require_once __DIR__ . '/../../src/Helpers/InlineEditHelper.php';
require_once __DIR__ . '/../components/hero_section.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Get editable content for landing page
$hero_content = render_editable_content('landing', 'hero_title', 'Velkommen til Nasjonal 15m Jaktfeltcup!', 'Innendørs jaktfelt for alle – november til februar. Her finner du resultater, påmelding og informasjon.');
$main_nav_content = render_editable_content('landing', 'main_nav_title', 'Hovednavigasjon', 'Velg din rolle og utforsk mulighetene i Nasjonal 15m Jaktfeltcup.');
$sponsors_content = render_editable_content('landing', 'sponsors_title', 'Våre Sponsorer', 'Takk til våre fantastiske sponsorer som gjør Nasjonal 15m Jaktfeltcup mulig.');
$news_content = render_editable_content('landing', 'news_title', 'Siste Nytt', 'Hold deg oppdatert med de siste nyhetene fra Nasjonal 15m Jaktfeltcup.');

// Check for logout message
$logout_message = '';
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $logout_message = 'Du har blitt logget ut. Velkommen tilbake!';
}

// Get upcoming competitions
try {
    $upcomingCompetitions = $database->queryAll(
        "SELECT * FROM jaktfelt_competitions 
         WHERE competition_date > NOW() 
         ORDER BY competition_date ASC 
         LIMIT 3"
    );
} catch (Exception $e) {
    // If database table doesn't exist or has wrong structure, use empty array
    $upcomingCompetitions = [];
    error_log("Landing page - Could not fetch competitions: " . $e->getMessage());
}

// Get latest news from database
try {
    $latestNews = $database->queryAll(
        "SELECT title, excerpt, created_at FROM jaktfelt_news 
         WHERE is_published = 1 
         ORDER BY created_at DESC 
         LIMIT 3"
    );
} catch (Exception $e) {
    // Fallback to placeholder data if table doesn't exist
    $latestNews = [
        [
            'title' => 'Jaktfeltcup 2024 er i gang!',
            'excerpt' => 'Første stevne av året er avholdt med stor suksess.',
            'created_at' => '2024-01-15 10:00:00'
        ],
        [
            'title' => 'Nye arrangører melder seg på',
            'excerpt' => 'Flere nye arrangører har meldt seg på for 2024-sesongen.',
            'created_at' => '2024-01-10 10:00:00'
        ]
    ];
    error_log("Landing page - Could not fetch news: " . $e->getMessage());
}
?>

<?php include_header(); ?>

<?php include_hero_section('landing', 'hero_title', $hero_content['title'], $hero_content['content']); ?>

<?php if (!empty($logout_message)): ?>
<!-- Logout Message -->
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= htmlspecialchars($logout_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php endif; ?>

<!-- Main Navigation Section -->
<section class="py-5" style="background-color: rgba(248, 249, 250, 0.7);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <?php if (can_edit_inline() && !empty($main_nav_content['editor_html'])): ?>
                    <?= $main_nav_content['editor_html'] ?>
                <?php else: ?>
                    <h2 class="mb-4"><?= htmlspecialchars($main_nav_content['title']) ?></h2>
                    <p class="lead text-muted mb-5"><?= htmlspecialchars($main_nav_content['content']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-calendar-plus fa-4x text-primary mb-3"></i>
                        <h4 class="card-title">Arrangør</h4>
                        <p class="card-text text-muted">
                            Bli arrangør og bidra til utvikling av skyteidretten
                        </p>
                        <a href="<?= base_url('arrangor') ?>" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Bli arrangør
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-handshake fa-4x text-warning mb-3"></i>
                        <h4 class="card-title">Sponsor</h4>
                        <p class="card-text text-muted">
                            Bli sponsor og få eksponering i et engasjert publikum
                        </p>
                        <a href="<?= base_url('sponsor') ?>" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-handshake me-2"></i>Bli sponsor
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-user-plus fa-4x text-success mb-3"></i>
                        <h4 class="card-title">Deltaker</h4>
                        <p class="card-text text-muted">
                            Meld deg på og konkurrer på alle nivåer
                        </p>
                        <a href="<?= base_url('deltaker') ?>" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-user-plus me-2"></i>Meld deg på
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-eye fa-4x text-info mb-3"></i>
                        <h4 class="card-title">Publikum</h4>
                        <p class="card-text text-muted">
                            Følg med på resultater og nyheter
                        </p>
                        <a href="<?= base_url('publikum') ?>" class="btn btn-info btn-lg w-100">
                            <i class="fas fa-eye me-2"></i>Se mer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sponsors Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-4">
                <?php if (can_edit_inline() && !empty($sponsors_content['editor_html'])): ?>
                    <?= $sponsors_content['editor_html'] ?>
                <?php else: ?>
                    <h3 class="mb-3"><?= htmlspecialchars($sponsors_content['title']) ?></h3>
                    <p class="text-muted"><?= htmlspecialchars($sponsors_content['content']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <?php
            // Get sponsor images
            $sponsorImages = \Jaktfeltcup\Helpers\ImageHelper::getSponsorImages();
            
            if (!empty($sponsorImages)): 
                foreach ($sponsorImages as $sponsor): 
            ?>
                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-3">
                    <div class="text-center">
                        <img src="<?= htmlspecialchars($sponsor['logo_url']) ?>" 
                             alt="<?= htmlspecialchars($sponsor['name']) ?>" 
                             class="img-fluid mb-2" 
                             style="max-height: 80px; object-fit: contain;">
                        <h6 class="small mb-0"><?= htmlspecialchars($sponsor['name']) ?></h6>
                    </div>
                </div>
            <?php 
                endforeach; 
            else: 
            ?>
                <div class="col-12">
                    <div class="alert alert-light text-center py-3" role="alert">
                        <i class="fas fa-handshake fa-2x mb-2 text-muted"></i>
                        <h6 class="mb-1">Bli vår første sponsor!</h6>
                        <p class="mb-2 small text-muted">Vi søker engasjerte sponsorer som vil støtte utviklingen av skytesporten.</p>
                        <a href="<?= base_url('sponsor/kontakt') ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-envelope me-1"></i>Kontakt oss
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($sponsorImages)): ?>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="<?= base_url('sponsor/presentasjon') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-eye me-1"></i>Se alle sponsorer
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold">500+</h3>
                        <p class="text-muted">Aktive deltakere</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold">20+</h3>
                        <p class="text-muted">Stevner per år</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold">15+</h3>
                        <p class="text-muted">Arrangører</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-trophy fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold">5</h3>
                        <p class="text-muted">År med erfaring</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Competitions -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h2 class="mb-4">Kommende stevner</h2>
                <?php if (!empty($upcomingCompetitions)): ?>
                    <div class="row">
                        <?php foreach ($upcomingCompetitions as $competition): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($competition['name']) ?></h5>
                                        <p class="card-text">
                                            <i class="fas fa-calendar me-2"></i>
                                            <?= date('d.m.Y', strtotime($competition['competition_date'])) ?>
                                        </p>
                                        <p class="card-text">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            <?= htmlspecialchars($competition['location']) ?>
                                        </p>
                                        <a href="<?= base_url('results/' . $competition['id']) ?>" class="btn btn-primary">
                                            Se detaljer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Ingen kommende stevner registrert ennå.
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-4">
                <?php if (can_edit_inline() && !empty($news_content['editor_html'])): ?>
                    <?= $news_content['editor_html'] ?>
                <?php else: ?>
                    <h2 class="mb-4"><?= htmlspecialchars($news_content['title']) ?></h2>
                    <p class="text-muted mb-4"><?= htmlspecialchars($news_content['content']) ?></p>
                <?php endif; ?>
                <?php foreach ($latestNews as $news): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><?= htmlspecialchars($news['title']) ?></h6>
                            <p class="card-text small text-muted">
                                <?= date('d.m.Y', strtotime($news['created_at'])) ?>
                            </p>
                            <p class="card-text small">
                                <?= htmlspecialchars($news['excerpt']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-4">Bli med i Jaktfeltcup!</h2>
                <p class="lead mb-4">
                    Enten du vil arrangere, sponse, delta eller bare følge med - 
                    det er plass for alle i Jaktfeltcup-familien.
                </p>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('arrangor') ?>" class="btn btn-light btn-lg w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Bli arrangør
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('sponsor') ?>" class="btn btn-outline-light btn-lg w-100">
                            <i class="fas fa-handshake me-2"></i>Bli sponsor
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('deltaker') ?>" class="btn btn-outline-light btn-lg w-100">
                            <i class="fas fa-user-plus me-2"></i>Meld deg på
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('publikum') ?>" class="btn btn-outline-light btn-lg w-100">
                            <i class="fas fa-eye me-2"></i>Følg med
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="text-center">
                    <i class="fas fa-trophy fa-3x text-primary mb-3"></i>
                    <h4>Premier og utmerkelser</h4>
                    <p class="text-muted">
                        Konkurrer om premier og utmerkelser på alle nivåer. 
                        Fra nybegynner til ekspert.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="text-center">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h4>Fellesskap</h4>
                    <p class="text-muted">
                        Bli del av et sterkt fellesskap av skyttere 
                        som deler samme lidenskap.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="text-center">
                    <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                    <h4>Utvikling</h4>
                    <p class="text-muted">
                        Forbedre dine ferdigheter gjennom regelmessig 
                        trening og konkurranse.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_footer(); ?>
