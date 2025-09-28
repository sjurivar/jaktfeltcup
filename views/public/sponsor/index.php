<?php
// Set page variables
$page_title = 'Bli Sponsor - Jaktfeltcup';
$page_description = 'Bli sponsor for Jaktfeltcup og få eksponering i Norges største skytekonkurranse.';
$current_page = 'sponsor';

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
                <h1 class="display-4 fw-bold mb-4">Bli Sponsor for Jaktfeltcup</h1>
                <p class="lead mb-4">
                    Få eksponering i Norges største skytekonkurranse. 
                    Som sponsor får du tilgang til et engasjert publikum og kan bygge ditt merke.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="<?= base_url('sponsor/pakker') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-star me-2"></i>Se sponsor-pakker
                    </a>
                    <a href="<?= base_url('sponsor/kontakt') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>Kontakt oss
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-handshake fa-8x opacity-50"></i>
            </div>
        </div>
    </div>
</section>

<!-- Why Sponsor -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Hvorfor sponse Jaktfeltcup?</h2>
                <p class="lead text-muted mb-5">
                    Jaktfeltcup er Norges største skytekonkurranse med over 500 aktive deltakere 
                    og 20+ stevner per år. Som sponsor får du tilgang til et engasjert og lojalt publikum.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Stort publikum</h5>
                        <p class="card-text">
                            Over 500 aktive deltakere og tusenvis av tilskuere 
                            gjennom året.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Voksende marked</h5>
                        <p class="card-text">
                            Skyteidrett er et voksende marked med økende 
                            interesse og deltakelse.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-heart fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Engasjert publikum</h5>
                        <p class="card-text">
                            Vårt publikum er engasjert og lojalt, 
                            med høy interesse for skyteidrett.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sponsor Packages -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Sponsor-pakker</h2>
                <p class="lead text-muted mb-5">
                    Vi tilbyr fleksible sponsor-pakker som kan tilpasses dine behov og budsjett.
                </p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark text-center">
                        <h4 class="mb-0">Bronze</h4>
                        <p class="mb-0">Fra 10.000 kr</p>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Logo på nettside</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Logo på resultatlister</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>E-post markedsføring</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>SoMe-eksponering</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 border-primary">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Silver</h4>
                        <p class="mb-0">Fra 25.000 kr</p>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Alt i Bronze</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Logo på premier</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Stand på stevner</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Eksklusiv markedsføring</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark text-center">
                        <h4 class="mb-0">Gold</h4>
                        <p class="mb-0">Fra 50.000 kr</p>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Alt i Silver</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Hovedsponsor status</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Eksklusiv stevne-navngivning</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Personlig kontaktperson</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= base_url('sponsor/pakker') ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-star me-2"></i>Se alle pakker
            </a>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h2 class="mb-4">Fordeler med å sponse</h2>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Eksponering:</strong> Få synlighet for ditt merke i et engasjert publikum
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Networking:</strong> Møt potensielle kunder og partnere
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>CSR:</strong> Bidra til utvikling av skyteidrett i Norge
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Målgruppe:</strong> Nå et spesialisert og engasjert publikum
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Fleksibilitet:</strong> Tilpasset sponsor-pakker for dine behov
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Våre nåværende sponsorer</h5>
                        <p class="card-text">Vi er stolte av å samarbeide med:</p>
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="bg-light p-3 rounded">
                                    <i class="fas fa-building fa-2x text-primary"></i>
                                    <p class="mb-0 mt-2">Bedrift A</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-light p-3 rounded">
                                    <i class="fas fa-building fa-2x text-primary"></i>
                                    <p class="mb-0 mt-2">Bedrift B</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-light p-3 rounded">
                                    <i class="fas fa-building fa-2x text-primary"></i>
                                    <p class="mb-0 mt-2">Bedrift C</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="bg-light p-3 rounded">
                                    <i class="fas fa-building fa-2x text-primary"></i>
                                    <p class="mb-0 mt-2">Bedrift D</p>
                                </div>
                            </div>
                        </div>
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
                <h2 class="mb-4">Klar til å bli sponsor?</h2>
                <p class="lead mb-4">
                    Ta kontakt med oss for å diskutere muligheter for samarbeid.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="<?= base_url('sponsor/pakker') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-star me-2"></i>Se sponsor-pakker
                    </a>
                    <a href="<?= base_url('sponsor/kontakt') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>Kontakt oss
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_footer(); ?>
