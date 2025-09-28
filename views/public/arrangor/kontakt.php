<?php
// Set page variables
$page_title = 'Kontakt Arrangører - Jaktfeltcup';
$page_description = 'Kontakt oss for å diskutere muligheter som arrangør for Jaktfeltcup.';
$current_page = 'arrangor_kontakt';

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
                    <h3 class="mb-0"><i class="fas fa-envelope me-2"></i>Kontakt Arrangører</h3>
                </div>
                <div class="card-body">
                    <p class="lead">Har du spørsmål om å bli arrangør? Vi hjelper deg gjerne!</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                                    <h5>E-post</h5>
                                    <p class="text-muted">Send oss en e-post</p>
                                    <a href="mailto:arrangor@jaktfeltcup.no" class="btn btn-outline-primary">
                                        arrangor@jaktfeltcup.no
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
                                    Hva kreves for å bli arrangør?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Du trenger ikke spesielle kvalifikasjoner, men det hjelper å ha erfaring med skyteidrett. 
                                    Vi gir opplæring i alt som trengs for å arrangere et stevne.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    Hvor ofte må jeg arrangere stevner?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Det er ingen krav til hvor ofte du må arrangere. Du kan arrangere så mange eller 
                                    så få stevner som du ønsker. Vi er fleksible og tilpasser oss dine behov.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    Får jeg støtte fra Jaktfeltcup?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ja, vi gir full støtte til alle våre arrangører. Dette inkluderer markedsføring, 
                                    teknisk støtte, resultatsystem og kontinuerlig oppfølging.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                    Hvor lang tid tar det å bli arrangør?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Prosessen tar vanligvis 2-4 uker fra første kontakt til du er klar til å arrangere. 
                                    Dette inkluderer møte, opplæring og planlegging av ditt første stevne.
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
