<?php
// Set page variables
$page_title = 'Jaktfeltcup - Norges største skytekonkurranse';
$page_description = 'Delta i Norges største skytekonkurranse. Bli arrangør, sponsor eller deltaker i Jaktfeltcup.';
$current_page = 'landing';

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new Jaktfeltcup\Core\Database($db_config);

// Get upcoming competitions
$upcomingCompetitions = $database->queryAll(
    "SELECT * FROM jaktfelt_competitions 
     WHERE competition_date > NOW() 
     ORDER BY competition_date ASC 
     LIMIT 3"
);

// Get latest news (placeholder for now)
$latestNews = [
    [
        'title' => 'Jaktfeltcup 2024 er i gang!',
        'date' => '2024-01-15',
        'excerpt' => 'Første stevne av året er avholdt med stor suksess.'
    ],
    [
        'title' => 'Nye arrangører melder seg på',
        'date' => '2024-01-10',
        'excerpt' => 'Flere nye arrangører har meldt seg på for 2024-sesongen.'
    ]
];
?>

<?php include_header(); ?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Jaktfeltcup 2024</h1>
                <p class="lead mb-4">
                    Norges største skytekonkurranse for alle nivåer. 
                    Bli med på en spennende reise med konkurranse, fellesskap og utvikling.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= base_url('deltaker') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Meld deg på
                    </a>
                    <a href="<?= base_url('arrangor') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Bli arrangør
                    </a>
                    <a href="<?= base_url('sponsor') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-handshake me-2"></i>Bli sponsor
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-bullseye fa-10x opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
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
                <h2 class="mb-4">Siste nytt</h2>
                <?php foreach ($latestNews as $news): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><?= htmlspecialchars($news['title']) ?></h6>
                            <p class="card-text small text-muted">
                                <?= date('d.m.Y', strtotime($news['date'])) ?>
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
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="<?= base_url('deltaker') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Meld deg på som deltaker
                    </a>
                    <a href="<?= base_url('arrangor') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Bli arrangør
                    </a>
                    <a href="<?= base_url('sponsor') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-handshake me-2"></i>Bli sponsor
                    </a>
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
