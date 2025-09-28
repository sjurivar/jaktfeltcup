<?php
// Set page variables
$page_title = 'Bli Deltaker - Jaktfeltcup';
$page_description = 'Meld deg på som deltaker i Jaktfeltcup og bli del av Norges største skytekonkurranse.';
$current_page = 'deltaker';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../../src/Core/Database.php';

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
?>

<?php include_header(); ?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Bli Deltaker i Jaktfeltcup</h1>
                <p class="lead mb-4">
                    Meld deg på som deltaker i Norges største skytekonkurranse. 
                    Enten du er nybegynner eller erfaren skytter - det er plass for alle!
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= base_url('deltaker/meld-deg-pa') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Meld deg på
                    </a>
                    <a href="<?= base_url('deltaker/regler') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-book me-2"></i>Se regler
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-user-plus fa-8x opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Why Participate -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Hvorfor delta i Jaktfeltcup?</h2>
                <p class="lead text-muted mb-5">
                    Jaktfeltcup tilbyr en unik mulighet til å utvikle dine ferdigheter, 
                    møte andre skyttere og konkurrere på alle nivåer.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Utvikling</h5>
                        <p class="card-text">
                            Forbedre dine ferdigheter gjennom regelmessig trening 
                            og konkurranse på alle nivåer.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Fellesskap</h5>
                        <p class="card-text">
                            Bli del av et sterkt fellesskap av skyttere 
                            som deler samme lidenskap og mål.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-trophy fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Premier</h5>
                        <p class="card-text">
                            Konkurrer om premier og utmerkelser på alle nivåer, 
                            fra nybegynner til ekspert.
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
                    Se hvilke stevner som kommer opp og meld deg på de som passer deg.
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
                                    <a href="<?= base_url('deltaker/meld-deg-pa') ?>" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-2"></i>Meld deg på
                                    </a>
                                    <a href="<?= base_url('results/' . $competition['id']) ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-info-circle me-2"></i>Se detaljer
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

<!-- How to Participate -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Hvordan delta?</h2>
                <p class="lead text-muted mb-5">
                    Prosessen for å bli deltaker er enkel og vi hjelper deg gjennom hele veien.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold">1</span>
                    </div>
                    <h5>Registrer deg</h5>
                    <p class="text-muted">Opprett en bruker på vår nettside.</p>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold">2</span>
                    </div>
                    <h5>Velg stevne</h5>
                    <p class="text-muted">Velg hvilke stevner du ønsker å delta på.</p>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold">3</span>
                    </div>
                    <h5>Meld deg på</h5>
                    <p class="text-muted">Fyll ut påmeldingsskjemaet for hvert stevne.</p>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold">4</span>
                    </div>
                    <h5>Delta</h5>
                    <p class="text-muted">Møt opp på stevnet og konkurrer!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rules and Requirements -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="mb-4">Regler og krav</h2>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Skyteerfaring:</strong> Ingen minimumskrav, alle nivåer velkommen
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Utstyr:</strong> Egen skytevåpen og sikkerhetsutstyr
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Lisens:</strong> Gyldig skytevåpenlisens
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Alder:</strong> Minimum 16 år (med samtykke fra foresatte)
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Sikkerhet:</strong> Følg alle sikkerhetsregler og instrukser
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Viktig informasjon</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Påmelding må skje minst 48 timer før stevnet</li>
                            <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Alle deltakere må ha gyldig forsikring</li>
                            <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Stevneavgift betales ved påmelding</li>
                            <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Avmelding kan skje inntil 24 timer før stevnet</li>
                            <li class="mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Resultater publiseres umiddelbart etter stevnet</li>
                        </ul>
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
                <h2 class="mb-4">Klar til å bli deltaker?</h2>
                <p class="lead mb-4">
                    Meld deg på i dag og bli del av Norges største skytekonkurranse!
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="<?= base_url('deltaker/meld-deg-pa') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Meld deg på
                    </a>
                    <a href="<?= base_url('deltaker/regler') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-book me-2"></i>Se regler
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_footer(); ?>
