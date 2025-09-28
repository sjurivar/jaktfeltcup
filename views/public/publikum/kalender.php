<?php
// Set page variables
$page_title = 'Stevne-kalender - Jaktfeltcup';
$page_description = 'Se alle kommende stevner i Jaktfeltcup. Planlegg ditt besøk eller påmelding.';
$current_page = 'publikum_kalender';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new Jaktfeltcup\Core\Database($db_config);

// Get all upcoming competitions
$upcomingCompetitions = $database->queryAll(
    "SELECT * FROM jaktfelt_competitions 
     WHERE competition_date > NOW() 
     ORDER BY competition_date ASC"
);

// Get past competitions (last 3 months)
$pastCompetitions = $database->queryAll(
    "SELECT * FROM jaktfelt_competitions 
     WHERE competition_date < NOW() 
     AND competition_date > DATE_SUB(NOW(), INTERVAL 3 MONTH)
     ORDER BY competition_date DESC 
     LIMIT 5"
);
?>

<?php include_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Stevne-kalender</h3>
                </div>
                <div class="card-body">
                    <p class="lead">Se alle kommende stevner i Jaktfeltcup og planlegg ditt besøk.</p>
                    
                    <!-- Upcoming Competitions -->
                    <h4 class="mt-4 mb-3">Kommende stevner</h4>
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
                                            <p class="card-text">
                                                <i class="fas fa-clock me-2"></i>
                                                <?= htmlspecialchars($competition['start_time'] ?? 'TBA') ?>
                                            </p>
                                            <?php if (!empty($competition['description'])): ?>
                                                <p class="card-text">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <?= htmlspecialchars($competition['description']) ?>
                                                </p>
                                            <?php endif; ?>
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
                    
                    <!-- Past Competitions -->
                    <?php if (!empty($pastCompetitions)): ?>
                        <h4 class="mt-5 mb-3">Nylig avholdte stevner</h4>
                        <div class="row">
                            <?php foreach ($pastCompetitions as $competition): ?>
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
                                            <div class="d-grid">
                                                <a href="<?= base_url('results/' . $competition['id']) ?>" class="btn btn-outline-primary">
                                                    <i class="fas fa-chart-bar me-2"></i>Se resultater
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Calendar Legend -->
                    <div class="mt-5">
                        <h5>Forklaring</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                <strong>Dato:</strong> Stevnet avholdes på denne datoen
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <strong>Sted:</strong> Hvor stevnet avholdes
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>Tid:</strong> Når stevnet starter
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <strong>Beskrivelse:</strong> Tilleggsinformasjon om stevnet
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="alert alert-info mt-4">
                        <h5><i class="fas fa-info-circle me-2"></i>Spørsmål om stevner?</h5>
                        <p class="mb-0">
                            Har du spørsmål om et spesifikt stevne? Kontakt arrangøren direkte eller 
                            send oss en e-post på <a href="mailto:info@jaktfeltcup.no">info@jaktfeltcup.no</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_footer(); ?>
