<?php
// Set page variables
$page_title = 'Publikum - Nasjonal 15m Jaktfeltcup';
$page_description = 'Følg med på Nasjonal 15m Jaktfeltcup - innendørs jaktfelt-konkurransen. Se resultater, stevne-kalender og nyheter.';
$current_page = 'publikum';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../../src/Core/Database.php';
require_once __DIR__ . '/../../../src/Helpers/InlineEditHelper.php';
require_once __DIR__ . '/../../components/hero_section.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Get editable content for publikum page
$hero_content = render_editable_content('publikum', 'hero_title', 'Velkommen til Nasjonal 15m Jaktfeltcup', 'Følg med på innendørs jaktfelt-konkurransen som foregår november-februar. Se resultater, stevne-kalender og nyheter.');
$results_content = render_editable_content('publikum', 'results_title', 'Resultater', 'Se resultater fra alle stevner og følg med på cupen.');
$calendar_content = render_editable_content('publikum', 'calendar_title', 'Stevne-kalender', 'Oversikt over alle kommende stevner i Jaktfeltcup.');
$news_content = render_editable_content('publikum', 'news_title', 'Siste Nytt', 'Hold deg oppdatert med de siste nyhetene fra Jaktfeltcup.');

// Get upcoming competitions
try {
    $upcomingCompetitions = $database->queryAll(
        "SELECT * FROM jaktfelt_competitions 
         WHERE competition_date > NOW() 
         ORDER BY competition_date ASC 
         LIMIT 3"
    );
} catch (Exception $e) {
    $upcomingCompetitions = [];
    error_log("Publikum page - Could not fetch competitions: " . $e->getMessage());
}

// Get latest news (placeholder for now)
$latestNews = [
    [
        'title' => 'Jaktfeltcup 2024 er i gang!',
        'date' => '2024-01-15',
        'excerpt' => 'Første stevne av året er avholdt med stor suksess. Over 100 deltakere møtte opp.',
        'image' => 'https://via.placeholder.com/300x200?text=Jaktfeltcup+2024'
    ],
    [
        'title' => 'Nye rekorder satt i Bergen',
        'date' => '2024-01-10',
        'excerpt' => 'Flere nye rekorder ble satt under stevnet i Bergen i helgen.',
        'image' => 'https://via.placeholder.com/300x200?text=Bergen+Stevne'
    ],
    [
        'title' => 'Arrangører melder seg på',
        'date' => '2024-01-05',
        'excerpt' => 'Flere nye arrangører har meldt seg på for 2024-sesongen.',
        'image' => 'https://via.placeholder.com/300x200?text=Nye+Arrangører'
    ]
];
?>

<?php include_header(); ?>

<?php 
$hero_buttons = [
    [
        'text' => 'Stevne-kalender',
        'url' => base_url('publikum/kalender'),
        'class' => 'btn-light',
        'icon' => 'fas fa-calendar-alt'
    ],
    [
        'text' => 'Se resultater',
        'url' => base_url('results'),
        'class' => 'btn-outline-light',
        'icon' => 'fas fa-chart-bar'
    ],
    [
        'text' => 'Nyheter',
        'url' => base_url('publikum/nyheter'),
        'class' => 'btn-outline-light',
        'icon' => 'fas fa-newspaper'
    ]
];
include_hero_section('publikum', 'hero_title', $hero_content['title'], $hero_content['content'], $hero_buttons);
?>

<!-- What is Jaktfeltcup -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Hva er Jaktfeltcup?</h2>
                <p class="lead text-muted mb-5">
                    Nasjonal 15m Jaktfeltcup er en innendørs jaktfelt-konkurranse med aktiv deltakelse 
                    og stevner gjennom vinterhalvåret. Vi tilbyr konkurranse på alle nivåer, fra nybegynner til ekspert.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Fellesskap</h5>
                        <p class="card-text">
                            Bli del av et sterkt fellesskap av skyttere som deler 
                            samme lidenskap og mål.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-trophy fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Konkurranse</h5>
                        <p class="card-text">
                            Konkurrer på alle nivåer og utvikle dine ferdigheter 
                            gjennom regelmessig trening.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Utvikling</h5>
                        <p class="card-text">
                            Forbedre dine ferdigheter og nå nye høyder 
                            gjennom strukturert konkurranse.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Competitions -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Kommende stevner</h2>
                <p class="lead text-muted mb-5">
                    Se hvilke stevner som kommer opp og planlegg ditt besøk.
                </p>
            </div>
        </div>
        
        <?php if (!empty($upcomingCompetitions)): ?>
            <div class="row">
                <?php foreach ($upcomingCompetitions as $competition): ?>
                    <div class="col-lg-4 mb-4">
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
                                <p class="card-text">
                                    <i class="fas fa-clock me-2"></i>
                                    <?= htmlspecialchars($competition['start_time'] ?? 'TBA') ?>
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('results/' . $competition['id']) ?>" class="btn btn-primary">
                                        <i class="fas fa-info-circle me-2"></i>Se detaljer
                                    </a>
                                    <a href="<?= base_url('deltaker/meld-deg-pa') ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-user-plus me-2"></i>Meld deg på
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Ingen kommende stevner registrert ennå. Sjekk tilbake senere!
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Latest News -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Siste nytt</h2>
                <p class="lead text-muted mb-5">
                    Hold deg oppdatert på det som skjer i Jaktfeltcup.
                </p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($latestNews as $news): ?>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= htmlspecialchars($news['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($news['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($news['title']) ?></h5>
                            <p class="card-text small text-muted">
                                <i class="fas fa-calendar me-2"></i>
                                <?= date('d.m.Y', strtotime($news['date'])) ?>
                            </p>
                            <p class="card-text"><?= htmlspecialchars($news['excerpt']) ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="<?= base_url('publikum/nyheter') ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-arrow-right me-2"></i>Les mer
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= base_url('publikum/nyheter') ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-newspaper me-2"></i>Se alle nyheter
            </a>
        </div>
    </div>
</section>

<!-- Quick Links -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Hurtigtilgang</h2>
                <p class="lead text-muted mb-5">
                    Få rask tilgang til det du er ute etter.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                        <h5>Stevne-kalender</h5>
                        <p class="text-muted">Se alle kommende stevner</p>
                        <a href="<?= base_url('publikum/kalender') ?>" class="btn btn-outline-primary">
                            Se kalender
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-bar fa-3x text-primary mb-3"></i>
                        <h5>Resultater</h5>
                        <p class="text-muted">Se resultater fra alle stevner</p>
                        <a href="<?= base_url('results') ?>" class="btn btn-outline-primary">
                            Se resultater
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-trophy fa-3x text-primary mb-3"></i>
                        <h5>Sammenlagt</h5>
                        <p class="text-muted">Se sammenlagtstilling</p>
                        <a href="<?= base_url('standings') ?>" class="btn btn-outline-primary">
                            Se sammenlagt
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-newspaper fa-3x text-primary mb-3"></i>
                        <h5>Nyheter</h5>
                        <p class="text-muted">Hold deg oppdatert</p>
                        <a href="<?= base_url('publikum/nyheter') ?>" class="btn btn-outline-primary">
                            Se nyheter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-4">Bli del av Jaktfeltcup!</h2>
                <p class="lead mb-4">
                    Enten du vil delta, arrangere, sponse eller bare følge med - 
                    det er plass for alle i Jaktfeltcup-familien.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="<?= base_url('deltaker') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Bli deltaker
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

<?php include_footer(); ?>
