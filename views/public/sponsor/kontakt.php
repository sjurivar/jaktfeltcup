<?php
// Set page variables
$page_title = 'Kontakt Sponsorer - Jaktfeltcup';
$page_description = 'Kontakt oss for å diskutere sponsor-muligheter for Jaktfeltcup.';
$current_page = 'sponsor_kontakt';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
?>

<?php include_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-envelope me-2"></i>Kontakt Sponsorer</h3>
                </div>
                <div class="card-body">
                    <p class="lead">Har du spørsmål om sponsor-muligheter? Vi hjelper deg gjerne!</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                                    <h5>E-post</h5>
                                    <p class="text-muted">Send oss en e-post</p>
                                    <a href="mailto:sponsor@jaktfeltcup.no" class="btn btn-outline-primary">
                                        sponsor@jaktfeltcup.no
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                                    <h5>Telefon</h5>
                                    <p class="text-muted">Ring oss direkte</p>
                                    <a href="tel:+4712345678" class="btn btn-outline-primary">
                                        +47 123 45 678
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h4 class="mb-3">Ofte stilte spørsmål</h4>
                    
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    Hva inkluderer sponsor-pakkene?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Våre sponsor-pakker inkluderer logo-eksponering, markedsføring, 
                                    stand-muligheter og tilpassede tjenester avhengig av pakkenivå.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    Kan jeg tilpasse sponsor-pakken?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ja, vi tilbyr fleksible løsninger som kan tilpasses dine behov og budsjett. 
                                    Vi jobber med deg for å finne den beste løsningen.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    Hvor stor er målgruppen?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Jaktfeltcup har over 500 aktive deltakere og tusenvis av tilskuere gjennom året. 
                                    Vårt publikum er engasjert og lojalt med høy interesse for skyteidrett.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                    Hvor langt i forveien må jeg bestille?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Vi anbefaler å kontakte oss minst 2-3 måneder i forveien for å sikre optimal 
                                    planlegging og implementering av sponsor-avtalen.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_footer(); ?>
