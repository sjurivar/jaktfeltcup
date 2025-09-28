<?php
// Set page variables
$page_title = 'Sponsor-pakker - Jaktfeltcup';
$page_description = 'Se våre fleksible sponsor-pakker for Jaktfeltcup. Tilpasset for alle behov og budsjetter.';
$current_page = 'sponsor_pakker';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
?>

<?php include_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="mb-4">Sponsor-pakker</h1>
            <p class="lead text-muted mb-5">
                Vi tilbyr fleksible sponsor-pakker som kan tilpasses dine behov og budsjett. 
                Alle pakker inkluderer grunnleggende eksponering og kan utvides med tilleggstjenester.
            </p>
        </div>
    </div>
    
    <div class="row">
        <!-- Bronze Package -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark text-center">
                    <h3 class="mb-0">Bronze</h3>
                    <p class="mb-0">Fra 10.000 kr</p>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Logo på nettside:</strong> Synlig logo på alle sider
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Logo på resultatlister:</strong> Eksponering på alle resultater
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>E-post markedsføring:</strong> Inkludert i nyhetsbrev
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>SoMe-eksponering:</strong> Facebook og Instagram
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Stevne-program:</strong> Logo i trykte programmer
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="<?= base_url('sponsor/kontakt') ?>" class="btn btn-warning btn-lg">
                        <i class="fas fa-envelope me-2"></i>Kontakt oss
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Silver Package -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100 border-primary">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Silver</h3>
                    <p class="mb-0">Fra 25.000 kr</p>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Alt i Bronze:</strong> Alle grunnleggende tjenester
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Logo på premier:</strong> Eksponering på utdelte premier
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Stand på stevner:</strong> Mulighet for stand på utvalgte stevner
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Eksklusiv markedsføring:</strong> Dedikert markedsføringskampanje
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Stevne-navngivning:</strong> Navngivning av utvalgte stevner
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="<?= base_url('sponsor/kontakt') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>Kontakt oss
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Gold Package -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark text-center">
                    <h3 class="mb-0">Gold</h3>
                    <p class="mb-0">Fra 50.000 kr</p>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Alt i Silver:</strong> Alle tjenester fra Silver-pakken
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Hovedsponsor status:</strong> Eksklusiv hovedsponsor for sesongen
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Eksklusiv stevne-navngivning:</strong> Navngivning av alle stevner
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Personlig kontaktperson:</strong> Dedikert kontaktperson
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Eksklusiv markedsføring:</strong> Hovedfokus i all markedsføring
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="<?= base_url('sponsor/kontakt') ?>" class="btn btn-warning btn-lg">
                        <i class="fas fa-envelope me-2"></i>Kontakt oss
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Services -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Tilleggstjenester</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-plus text-primary me-2"></i>Eksklusiv stevne-navngivning</h6>
                            <p class="text-muted small">Navngi et stevne etter ditt merke</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-plus text-primary me-2"></i>Stand på alle stevner</h6>
                            <p class="text-muted small">Stand på alle stevner i sesongen</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-plus text-primary me-2"></i>Eksklusiv markedsføring</h6>
                            <p class="text-muted small">Dedikert markedsføringskampanje</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6><i class="fas fa-plus text-primary me-2"></i>Personlig kontaktperson</h6>
                            <p class="text-muted small">Dedikert kontaktperson for hele sesongen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact CTA -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto text-center">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-4">Interessert i å bli sponsor?</h3>
                    <p class="lead mb-4">
                        Vi tilbyr fleksible løsninger som kan tilpasses dine behov og budsjett. 
                        Ta kontakt for å diskutere muligheter.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="<?= base_url('sponsor/kontakt') ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-envelope me-2"></i>Kontakt oss
                        </a>
                        <a href="mailto:sponsor@jaktfeltcup.no" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-envelope me-2"></i>sponsor@jaktfeltcup.no
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_footer(); ?>
