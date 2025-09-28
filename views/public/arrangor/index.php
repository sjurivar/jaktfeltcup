<?php
// Set page variables
$page_title = 'Bli Arrangør - Jaktfeltcup';
$page_description = 'Bli arrangør for Jaktfeltcup og bidra til Norges største skytekonkurranse.';
$current_page = 'arrangor';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
?>

<?php include_header(); ?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Bli Arrangør for Jaktfeltcup</h1>
                <p class="lead mb-4">
                    Bli del av Norges største skytekonkurranse som arrangør. 
                    Vi trenger engasjerte arrangører som kan bidra til å utvikle skyteidretten.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= base_url('arrangor/bli-arrangor') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Bli arrangør nå
                    </a>
                    <a href="<?= base_url('arrangor/kontakt') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>Kontakt oss
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-calendar-alt fa-8x opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Why Become Organizer -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Hvorfor bli arrangør?</h2>
                <p class="lead text-muted mb-5">
                    Som arrangør for Jaktfeltcup får du mulighet til å bidra til utvikling av skyteidretten 
                    og skape verdifulle opplevelser for deltakere.
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
                            Bli del av et sterkt fellesskap av engasjerte arrangører 
                            som deler samme lidenskap for skyteidrett.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-trophy fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Prestisje</h5>
                        <p class="card-text">
                            Arranger stevner under Norges største skytekonkurranse 
                            og få anerkjennelse for ditt bidrag.
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
                            Bidra til utvikling av skyteidretten og skape 
                            muligheter for nye skyttere.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What You Get -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="mb-4">Hva får du som arrangør?</h2>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Komplett støtte:</strong> Vi hjelper deg med alt fra planlegging til gjennomføring
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Markedsføring:</strong> Vi markedsfører ditt stevne på alle våre kanaler
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Teknisk støtte:</strong> Hjelp med resultatsystem og administrasjon
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Networking:</strong> Møt andre arrangører og bygg nettverk
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Anerkjennelse:</strong> Få offisiell status som Jaktfeltcup-arrangør
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Arrangør-pakke inkluderer:</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Komplett stevne-oppsett</li>
                            <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Resultatsystem og administrasjon</li>
                            <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Markedsføring og promotering</li>
                            <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Teknisk støtte og opplæring</li>
                            <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Premier og utmerkelser</li>
                            <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Kontinuerlig oppfølging</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How to Become Organizer -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Hvordan bli arrangør?</h2>
                <p class="lead text-muted mb-5">
                    Prosessen for å bli arrangør er enkel og vi hjelper deg gjennom hele veien.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold">1</span>
                    </div>
                    <h5>Kontakt oss</h5>
                    <p class="text-muted">Send oss en melding om din interesse for å arrangere.</p>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold">2</span>
                    </div>
                    <h5>Møte</h5>
                    <p class="text-muted">Vi møtes for å diskutere muligheter og forventninger.</p>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold">3</span>
                    </div>
                    <h5>Opplæring</h5>
                    <p class="text-muted">Vi gir deg opplæring i systemer og prosedyrer.</p>
                </div>
            </div>
            <div class="col-lg-3 mb-4">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <span class="fw-bold">4</span>
                    </div>
                    <h5>Arranger</h5>
                    <p class="text-muted">Du er klar til å arrangere ditt første stevne!</p>
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
                <h2 class="mb-4">Klar til å bli arrangør?</h2>
                <p class="lead mb-4">
                    Ta kontakt med oss i dag og bli del av Jaktfeltcup-familien.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="<?= base_url('arrangor/bli-arrangor') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Bli arrangør nå
                    </a>
                    <a href="<?= base_url('arrangor/kontakt') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>Kontakt oss
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_footer(); ?>
