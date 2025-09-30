<?php
// Set page variables
$page_title = 'Om oss - Nasjonal 15m Jaktfeltcup';
$page_description = 'Lær mer om Nasjonal 15m Jaktfeltcup og hovedkomiteen som organiserer innendørs jaktfelt-konkurransen.';
$current_page = 'om-oss';

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../src/Helpers/InlineEditHelper.php';
require_once __DIR__ . '/../components/hero_section.php';

// Get editable content for about page
$hero_content = render_editable_content('om-oss', 'hero_title', 'Om Nasjonal 15m Jaktfeltcup', 'Lær mer om innendørs jaktfelt-konkurransen og hovedkomiteen som organiserer den.');
$intro_content = render_editable_content('om-oss', 'intro_title', 'Om Jaktfeltcup', 'Nasjonal 15m Jaktfeltcup er et nytt, landsdekkende konsept som samler jegere og skyttere gjennom vinterhalvåret.');
$cup_info_content = render_editable_content('om-oss', 'cup_info_title', 'Om cupen', 'Cupen består av fire innledende runder som skytes på 15m bane, med en stor finalehelg i Leikanger i februar.');
$committee_content = render_editable_content('om-oss', 'committee_title', 'Hovedkomiteen', 'Hovedkomiteen består av erfarne jaktskyttere og organisasjonsfolk som jobber for å utvikle skyteidretten.');
?>

<?php include_header(); ?>

<?php 
$hero_buttons = [
    [
        'text' => 'Se resultater',
        'url' => base_url('results'),
        'class' => 'btn-light',
        'icon' => 'fas fa-chart-line'
    ],
    [
        'text' => 'Bli deltaker',
        'url' => base_url('deltaker'),
        'class' => 'btn-outline-light',
        'icon' => 'fas fa-user-plus'
    ]
];
include_hero_section('om-oss', 'hero_title', $hero_content['title'], $hero_content['content'], $hero_buttons);
?>

<!-- Cup Information Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="card border-primary">
                            <div class="card-body">
                                <p class="lead mb-4">
                                    Nasjonal 15m Jaktfeltcup er et nytt, landsdekkende konsept som samler jegere og skyttere 
                                    gjennom vinterhalvåret. Cupen består av fire innledende runder som skytes på 15m bane, 
                                    med en stor finalehelg i Leikanger i februar.
                                </p>
                                <p class="mb-4">
                                    Målet er å skape aktivitet, rekruttering og samarbeid mellom NJFF og DFS, og å gi både unge 
                                    og voksne et sosialt og sportslig høydepunkt i vintersesongen.
                                </p>
                                
                                <h5 class="mb-3"><i class="fas fa-list-ul me-2 text-primary"></i>Hovedpunkter:</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>4 innledende runder</strong> (november–januar)</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Finalehelg i Leikanger</strong> i februar</li>
                                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>3 beste av 4 runder</strong> teller i sammendraget</li>
                                    <li class="mb-0"><i class="fas fa-check text-success me-2"></i><strong>Alle skyter på 15m bane</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Committee Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if (can_edit_inline() && !empty($committee_content['editor_html'])): ?>
                    <?= $committee_content['editor_html'] ?>
                <?php else: ?>
                    <h2 class="text-center mb-5"><?= htmlspecialchars($committee_content['title']) ?></h2>
                <?php endif; ?>
                
                <p class="text-center lead mb-5"><?= htmlspecialchars($committee_content['content']) ?></p>
            </div>
        </div>
        
        <!-- Committee Members -->
        <div class="row">
            <!-- Anne Britt Sollie Fladvad -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Anne Britt Sollie Fladvad</h5>
                        <h6 class="card-subtitle mb-3 text-muted">Leder / Organisatorisk ansvarlig</h6>
                        <p class="card-text">
                            <strong>39 år, fra Overhalla.</strong><br>
                            Aktiv DFS-kikkertskytter og jaktfeltskytter. En del av DFS-miljøet siden tidlig ungdomstid. 
                            Pådriver for etablering av kikkertklassen i DFS. Primus motor og initiativtaker for 
                            Jaktfeltkarusell Namdal (nå i sin tredje sesong). Brenner for rekruttering av barn, 
                            ungdom og voksne, og for lavterskeltilbud til den jevne jeger.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Har overordnet ansvar og leder møtene</li>
                            <li><i class="fas fa-check text-success me-2"></i>Holder framdrift og følger opp regionkontakter</li>
                            <li><i class="fas fa-check text-success me-2"></i>Representerer cupen utad</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Tore Rønsåsbjørg -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Tore Rønsåsbjørg</h5>
                        <h6 class="card-subtitle mb-3 text-muted">Sportslig + resultatansvarlig</h6>
                        <p class="card-text">
                            <strong>36 år, fra Flora i Stjørdal kommune.</strong><br>
                            Startet med DFS som 7-åring, etter hvert mer fokus på jakt og jaktskyting. 
                            Meritert jaktskytter – bl.a. Norgesmester i presskyting 2017. Jobber i anleggsbransjen. 
                            Hobbyer: jakt, skyting og musikk.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Ansvar for regelverk, klasser og løypeoppsett</li>
                            <li><i class="fas fa-check text-success me-2"></i>Følger opp rapportering og resultatlister</li>
                            <li><i class="fas fa-check text-success me-2"></i>Kvalitetssikrer sammenlignbarhet nasjonalt</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Nils Harald Huset -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Nils Harald Huset</h5>
                        <h6 class="card-subtitle mb-3 text-muted">Sportslig + resultatansvarlig</h6>
                        <p class="card-text">
                            <strong>42 år, født, oppvokst og bosatt i Hedalen i Valdres.</strong><br>
                            Startet med jaktfelt- og elgbaneskyting som 10-åring. Meritert jaktskytter – 
                            bl.a. Norgesmester i jaktfelt 2019. Driver gård og jobber i landbruket.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Ansvar for regelverk, klasser og løypeoppsett</li>
                            <li><i class="fas fa-check text-success me-2"></i>Følger opp rapportering og resultatlister</li>
                            <li><i class="fas fa-check text-success me-2"></i>Kvalitetssikrer sammenlignbarhet nasjonalt</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Lars Ove Dulsvik -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Lars Ove Dulsvik</h5>
                        <h6 class="card-subtitle mb-3 text-muted">Kommunikasjon + sponsor/økonomi</h6>
                        <p class="card-text">
                            <strong>26 år (snart 27), fra Sogndal.</strong><br>
                            Startet i DFS som 7-åring, de siste 10 årene mest fokus på jakt og jaktfelt. 
                            Medeier og serviceleder i elektrobedrift med 17 ansatte. Sterk lokal ildsjel med 
                            erfaring fra både organisasjonsliv og næringsliv.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Hovedansvar for profil, SoMe, informasjonsflyt og synlighet</li>
                            <li><i class="fas fa-check text-success me-2"></i>Utarbeider sponsorstrategi og følger opp avtaler</li>
                            <li><i class="fas fa-check text-success me-2"></i>Har oversikt over fellespott og økonomi</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Jakob Holen Eikeland -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Jakob Holen Eikeland</h5>
                        <h6 class="card-subtitle mb-3 text-muted">Finale- og arrangementansvarlig</h6>
                        <p class="card-text">
                            <strong>28 år, fra Leikanger i Sogn.</strong><br>
                            Jobber som anleggsleder, og skal ta over stor fruktgård. Levende opptatt av jakt og fiske, 
                            en interesse han har arvet fra bestefaren Jakob sr.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Koordinerer planlegging og gjennomføring av finalehelg</li>
                            <li><i class="fas fa-check text-success me-2"></i>Samarbeider med lokal arrangør (Leikanger)</li>
                            <li><i class="fas fa-check text-success me-2"></i>Sørger for bankett og praktiske rammer</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Joar Søhoel -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Joar Søhoel</h5>
                        <h6 class="card-subtitle mb-3 text-muted">Kommunikasjon + sponsor/økonomi</h6>
                        <p class="card-text">
                            <strong>Fra Hafslo i Sogn, utdannet kokk med 17 års erfaring i kokkefaget.</strong><br>
                            Grunnlegger av Jakt- & Viltmatkanalen, med fokus på jakt, fangst, villmat og inspirasjon til 
                            naturbruk. Aktiv som formidler og foredragsholder – kurs i partering, jaktfilm-foredrag og 
                            viltmatinnhold. Kobler jakt og friluftsliv med bred publikumstilknytning og digital synlighet.
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Hovedansvar for profil, SoMe, informasjonsflyt og synlighet</li>
                            <li><i class="fas fa-check text-success me-2"></i>Utarbeider sponsorstrategi og følger opp avtaler</li>
                            <li><i class="fas fa-check text-success me-2"></i>Har oversikt over fellespott og økonomi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-5" style="background-color: rgba(248, 249, 250, 0.7);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Kontakt oss</h2>
                <p class="lead mb-4">
                    Har du spørsmål om Jaktfeltcup eller ønsker å komme i kontakt med hovedkomiteen? 
                    Vi er her for å hjelpe deg!
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="<?= base_url('arrangor/kontakt') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>Kontakt arrangør
                    </a>
                    <a href="<?= base_url('sponsor/kontakt') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-handshake me-2"></i>Bli sponsor
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_footer(); ?>
