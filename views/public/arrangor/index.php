<?php
// Set page variables
$page_title = 'Bli Arrangør - Nasjonal 15m Jaktfeltcup';
$page_description = 'Bli arrangør for Nasjonal 15m Jaktfeltcup og bidra til innendørs jaktfelt-konkurransen.';
$current_page = 'arrangor';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../../src/Helpers/InlineEditHelper.php';
require_once __DIR__ . '/../../../src/Helpers/OrganizerHelper.php';
require_once __DIR__ . '/../../components/hero_section.php';

// Get organizers and rounds data
$roundsData = \Jaktfeltcup\Helpers\OrganizerHelper::getOrganizersWithEvents();

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Get editable content
$hero_content = render_editable_content('arrangor', 'hero_title', 'Bli arrangør for Nasjonal 15m Jaktfeltcup', 'Arranger innledende runder på din lokale 15m bane og bidra til rekruttering og samarbeid mellom NJFF og DFS.');
$benefits_content = render_editable_content('arrangor', 'benefits_title', 'Hvorfor bli arrangør?', 'Som arrangør for Jaktfeltcup får du mulighet til å bidra til utvikling av skyteidretten og skape verdifulle opplevelser for deltakere.');

// Card content
$fellesskap_content = render_editable_content('arrangor', 'fellesskap_title', 'Fellesskap', 'Bli del av et sterkt fellesskap av engasjerte arrangører som deler samme lidenskap for skyteidrett.');
$prestisje_content = render_editable_content('arrangor', 'prestisje_title', 'Prestisje', 'Arranger stevner under innendørs jaktfelt-konkurransen og få anerkjennelse for ditt bidrag.');
$utvikling_content = render_editable_content('arrangor', 'utvikling_title', 'Utvikling', 'Bidra til utvikling av skyteidretten og skape muligheter for nye skyttere.');

// What you get section
$what_you_get_content = render_editable_content('arrangor', 'what_you_get_title', 'Hva får du som arrangør?', 'Vi tilbyr komplett støtte, markedsføring, teknisk støtte, networking og anerkjennelse.');

// How to become organizer
$how_to_become_content = render_editable_content('arrangor', 'how_to_become_title', 'Hvordan bli arrangør?', 'Prosessen for å bli arrangør er enkel og vi hjelper deg gjennom hele veien.');

// Call to action
$cta_content = render_editable_content('arrangor', 'cta_title', 'Klar til å bli arrangør?', 'Ta kontakt med oss i dag og bli del av Jaktfeltcup-familien.');
?>

<?php include_header(); ?>

<?php 
$hero_buttons = [
    [
        'text' => 'Bli arrangør nå',
        'url' => base_url('arrangor/bli-arrangor'),
        'class' => 'btn-light',
        'icon' => 'fas fa-calendar-plus'
    ],
    [
        'text' => 'Kontakt oss',
        'url' => base_url('arrangor/kontakt'),
        'class' => 'btn-outline-light',
        'icon' => 'fas fa-envelope'
    ]
];
//include_hero_section('arrangor', 'hero_title', $hero_content['title'], $hero_content['content'], $hero_buttons);
include_hero_section('arrangor', 'hero_title', $hero_content['title'], $hero_content['content']);
?>

<!-- Why Become Organizer -->
 <!--
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <?php if (can_edit_inline() && !empty($benefits_content['editor_html'])): ?>
                    <?= $benefits_content['editor_html'] ?>
                <?php else: ?>
                    <h2 class="mb-4"><?= htmlspecialchars($benefits_content['title']) ?></h2>
                    <p class="lead text-muted mb-5"><?= htmlspecialchars($benefits_content['content']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <?php if (can_edit_inline() && !empty($fellesskap_content['editor_html'])): ?>
                            <?= $fellesskap_content['editor_html'] ?>
                        <?php else: ?>
                            <h5 class="card-title"><?= htmlspecialchars($fellesskap_content['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($fellesskap_content['content']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-trophy fa-3x text-primary mb-3"></i>
                        <?php if (can_edit_inline() && !empty($prestisje_content['editor_html'])): ?>
                            <?= $prestisje_content['editor_html'] ?>
                        <?php else: ?>
                            <h5 class="card-title"><?= htmlspecialchars($prestisje_content['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($prestisje_content['content']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <?php if (can_edit_inline() && !empty($utvikling_content['editor_html'])): ?>
                            <?= $utvikling_content['editor_html'] ?>
                        <?php else: ?>
                            <h5 class="card-title"><?= htmlspecialchars($utvikling_content['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($utvikling_content['content']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
-->

<!-- What You Get -->
 <!--
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <?php if (can_edit_inline() && !empty($what_you_get_content['editor_html'])): ?>
                    <?= $what_you_get_content['editor_html'] ?>
                <?php else: ?>
                    <h2 class="mb-4"><?= htmlspecialchars($what_you_get_content['title']) ?></h2>
                    <p class="lead"><?= htmlspecialchars($what_you_get_content['content']) ?></p>
                <?php endif; ?>
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
-->


<!-- Contact Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Bli arrangør</h2>
                <p class="lead text-muted mb-5">
                    Vi setter stor pris på å samarbeide med arrangører som ønsker å 
                    arrangere innledende runder i Nasjonal 15m Jaktfeltcup.
                </p>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-5">
                        <i class="fas fa-handshake fa-4x text-primary mb-4"></i>
                        <h3 class="mb-4">Ta kontakt med hovedkomiteen</h3>
                        <p class="mb-4">
                            For informasjon om å bli arrangør og hva som kreves, 
                            ta gjerne kontakt med oss. Vi hjelper deg med alt fra 
                            planlegging til gjennomføring.
                        </p>
                        
                        <div class="row mt-5">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-envelope fa-2x text-primary me-3"></i>
                                    <div class="text-start">
                                        <small class="text-muted d-block">E-post</small>
                                        <strong>post@jaktfeltcup.no</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-phone fa-2x text-primary me-3"></i>
                                    <div class="text-start">
                                        <small class="text-muted d-block">Kontakt oss</small>
                                        <strong>Se Om oss</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="<?= base_url('om-oss') ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-users me-2"></i>Se hovedkomiteen
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rounds and Events Schedule -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h2 class="text-center mb-4">Runder og stevner</h2>
                <p class="lead text-center text-muted mb-5">
                    Nasjonal 15m Jaktfeltcup består av 4 runder med flere stevner. 
                    De 3 beste resultatene teller for hver skytter.
                </p>
                
                <?php if (!empty($roundsData)): ?>
                    <?php foreach ($roundsData as $round): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h4 class="mb-0">
                                            <i class="fas fa-calendar-alt me-2"></i>
                                            <?= htmlspecialchars($round['round_name']) ?>
                                        </h4>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <small>
                                            <i class="fas fa-clock me-1"></i>
                                            <?= \Jaktfeltcup\Helpers\OrganizerHelper::formatDateRange($round['start_date'], $round['end_date']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Frist for innsending av resultater:</strong> 
                                    <?= \Jaktfeltcup\Helpers\OrganizerHelper::formatDate($round['result_deadline']) ?>
                                </div>
                                
                                <?php if (!empty($round['events'])): ?>
                                    <h5 class="mb-3">Stevner i denne runden:</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Dato</th>
                                                    <th>Stevne</th>
                                                    <th>Sted</th>
                                                    <th>Arrangør</th>
                                                    <th>Kontakt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($round['events'] as $event): ?>
                                                    <tr>
                                                        <td>
                                                            <i class="fas fa-calendar me-1 text-muted"></i>
                                                            <?= \Jaktfeltcup\Helpers\OrganizerHelper::formatDate($event['competition_date']) ?>
                                                        </td>
                                                        <td>
                                                            <strong><?= htmlspecialchars($event['competition_name']) ?></strong>
                                                        </td>
                                                        <td>
                                                            <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                                                            <?= htmlspecialchars($event['location']) ?>
                                                            <?php if ($event['city']): ?>
                                                                <br><small class="text-muted"><?= htmlspecialchars($event['city']) ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?= htmlspecialchars($event['organizer_name']) ?>
                                                            <br>
                                                            <small class="text-muted">
                                                                <?= \Jaktfeltcup\Helpers\OrganizerHelper::formatOrganizationType($event['organizer_type']) ?>
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <?php if ($event['email']): ?>
                                                                <a href="mailto:<?= htmlspecialchars($event['email']) ?>" class="text-decoration-none">
                                                                    <i class="fas fa-envelope me-1"></i>
                                                                    <?= htmlspecialchars($event['email']) ?>
                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if ($event['phone']): ?>
                                                                <br>
                                                                <small>
                                                                    <i class="fas fa-phone me-1"></i>
                                                                    <?= htmlspecialchars($event['phone']) ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                        <p>Ingen stevner planlagt for denne runden ennå.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Stevne-kalender for sesongen publiseres snart. 
                        Følg med for oppdateringer!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include_footer(); ?>
