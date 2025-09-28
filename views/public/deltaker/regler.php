<?php
// Set page variables
$page_title = 'Regler og krav - Jaktfeltcup';
$page_description = 'Regler og krav for deltakere i Jaktfeltcup.';
$current_page = 'deltaker_regler';

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
                    <h3 class="mb-0"><i class="fas fa-book me-2"></i>Regler og krav for deltakere</h3>
                </div>
                <div class="card-body">
                    <p class="lead">Følgende regler og krav gjelder for alle deltakere i Jaktfeltcup.</p>
                    
                    <!-- General Rules -->
                    <h4 class="mt-4 mb-3">Generelle regler</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Påmelding:</strong> Påmelding må skje minst 48 timer før stevnet
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Avmelding:</strong> Avmelding kan skje inntil 24 timer før stevnet
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Forsinkelse:</strong> Deltakere som kommer for sent kan ikke delta
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Resultater:</strong> Alle resultater publiseres umiddelbart etter stevnet
                        </li>
                    </ul>
                    
                    <!-- Safety Rules -->
                    <h4 class="mt-4 mb-3">Sikkerhetsregler</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-warning me-2"></i>
                            <strong>Skytevåpen:</strong> Alltid behandle skytevåpen som om de er ladd
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-warning me-2"></i>
                            <strong>Retning:</strong> Pek aldri skytevåpen mot noen eller noe du ikke vil skyte
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-warning me-2"></i>
                            <strong>Finger:</strong> Hold fingeren utenfor avtrekkeren til du er klar til å skyte
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-warning me-2"></i>
                            <strong>Ladning:</strong> Ladd kun når du er på skytebanen og har fått signal
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-warning me-2"></i>
                            <strong>Utlading:</strong> Utlad alltid skytevåpenet når du forlater skytebanen
                        </li>
                    </ul>
                    
                    <!-- Equipment Requirements -->
                    <h4 class="mt-4 mb-3">Utstyrskrav</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-tools text-info me-2"></i>
                            <strong>Skytevåpen:</strong> Egen skytevåpen i god stand
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-tools text-info me-2"></i>
                            <strong>Ammunisjon:</strong> Egen ammunisjon i tilstrekkelig mengde
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-tools text-info me-2"></i>
                            <strong>Hørselvern:</strong> Hørselvern er obligatorisk for alle deltakere
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-tools text-info me-2"></i>
                            <strong>Briller:</strong> Sikkerhetsbriller anbefales sterkt
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-tools text-info me-2"></i>
                            <strong>Kjøring:</strong> Egen kjøring til og fra stevnet
                        </li>
                    </ul>
                    
                    <!-- Age and License Requirements -->
                    <h4 class="mt-4 mb-3">Alder og lisenskrav</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-id-card text-primary me-2"></i>
                            <strong>Minimumsalder:</strong> 16 år (med samtykke fra foresatte)
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-id-card text-primary me-2"></i>
                            <strong>Skytevåpenlisens:</strong> Gyldig skytevåpenlisens påkrevd
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-id-card text-primary me-2"></i>
                            <strong>Forsikring:</strong> Gyldig forsikring som dekker skyteidrett
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-id-card text-primary me-2"></i>
                            <strong>Legitimasjon:</strong> Gyldig legitimasjon må kunne fremvises
                        </li>
                    </ul>
                    
                    <!-- Competition Rules -->
                    <h4 class="mt-4 mb-3">Konkurranseregler</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-trophy text-warning me-2"></i>
                            <strong>Kategorier:</strong> Deltakere konkurrerer i alders- og erfaringbaserte kategorier
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-trophy text-warning me-2"></i>
                            <strong>Poengsystem:</strong> Poeng tildeles basert på treff og nøyaktighet
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-trophy text-warning me-2"></i>
                            <strong>Premier:</strong> Premier deles ut til de beste i hver kategori
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-trophy text-warning me-2"></i>
                            <strong>Protester:</strong> Protester må fremmes umiddelbart etter resultatbekjentgjøring
                        </li>
                    </ul>
                    
                    <!-- Disqualification Rules -->
                    <h4 class="mt-4 mb-3">Diskvalifikasjon</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <strong>Sikkerhet:</strong> Brudd på sikkerhetsregler fører til umiddelbar diskvalifikasjon
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <strong>Utstyr:</strong> Bruk av ikke-godkjent utstyr kan føre til diskvalifikasjon
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <strong>Oppførsel:</strong> Uakseptabel oppførsel fører til diskvalifikasjon
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <strong>Doping:</strong> Bruk av forbudte stoffer fører til permanent utestengelse
                        </li>
                    </ul>
                    
                    <!-- Important Notes -->
                    <div class="alert alert-warning mt-4">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Viktig informasjon</h5>
                        <ul class="mb-0">
                            <li>Alle deltakere må følge instrukser fra stevneledere og dommere</li>
                            <li>Stevneavgift betales ved påmelding og er ikke refunderbar</li>
                            <li>Jaktfeltcup forbeholder seg retten til å endre regler og krav</li>
                            <li>Spørsmål om regler kan rettes til stevneledere eller organisasjonskomiteen</li>
                        </ul>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="alert alert-info mt-4">
                        <h5><i class="fas fa-info-circle me-2"></i>Kontakt</h5>
                        <p class="mb-0">
                            Har du spørsmål om regler eller krav? Kontakt oss på 
                            <a href="mailto:info@jaktfeltcup.no">info@jaktfeltcup.no</a> 
                            eller ring <a href="tel:+4712345678">+47 123 45 678</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_footer(); ?>
