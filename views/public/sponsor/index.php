<?php
// Set page variables
$page_title = 'Bli Sponsor - Nasjonal 15m Jaktfeltcup';
$page_description = 'Bli sponsor for Nasjonal 15m Jaktfeltcup og få eksponering i innendørs jaktfelt-konkurransen.';
$current_page = 'sponsor';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../../src/Helpers/InlineEditHelper.php';
require_once __DIR__ . '/../../components/hero_section.php';

// Get sponsor images
$sponsorImages = \Jaktfeltcup\Helpers\ImageHelper::getSponsorImages();

// Get editable content for sponsor page
$hero_content = render_editable_content('sponsor', 'hero_title', 'Bli sponsor for Nasjonal 15m Jaktfeltcup', 'Støtt rekruttering og samarbeid mellom NJFF og DFS. Få eksponering i en nasjonal konkurranse som foregår november-februar.');
$benefits_content = render_editable_content('sponsor', 'benefits_title', 'Hvorfor bli sponsor?', 'Som sponsor for Nasjonal 15m Jaktfeltcup får du tilgang til et engasjert publikum og kan bygge ditt merke.');
$packages_content = render_editable_content('sponsor', 'packages_title', 'Sponsorpakker', 'Velg den pakken som passer best for ditt selskap.');
$cta_content = render_editable_content('sponsor', 'cta_title', 'Klar til å bli sponsor?', 'Ta kontakt med oss i dag og bli del av Nasjonal 15m Jaktfeltcup.');
?>

<?php include_header(); ?>

<?php 
$hero_buttons = [
    [
        'text' => 'Se våre sponsorer',
        'url' => base_url('sponsor/presentasjon'),
        'class' => 'btn-light',
        'icon' => 'fas fa-handshake'
    ],
    [
        'text' => 'Kontakt hovedkomiteen',
        'url' => base_url('om-oss'),
        'class' => 'btn-outline-light',
        'icon' => 'fas fa-users'
    ]
];
include_hero_section('sponsor', 'hero_title', $hero_content['title'], $hero_content['content'], $hero_buttons);
?>
    
<!-- Why Sponsor -->
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
-->
<!-- Contact Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Bli sponsor</h2>
                <p class="lead text-muted mb-5">
                    Vi setter stor pris på å samarbeide med sponsorer som deler våre verdier 
                    og ønsker å støtte Nasjonal 15m Jaktfeltcup.
                </p>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-5">
                        <i class="fas fa-handshake fa-4x text-primary mb-4"></i>
                        <h3 class="mb-4">Ta kontakt med hovedkomiteen</h3>
                        <p class="mb-4">
                            For informasjon om sponsormuligheter og tilpassede pakker, 
                            ta gjerne kontakt med oss. Vi lager gjerne en løsning som 
                            passer for din bedrift.
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

<!-- Benefits -->

<section class="py-5">
    <div class="container">
        <div class="row">
            <!--
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
            -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Våre nåværende sponsorer</h5>
                        <p class="card-text">Vi er stolte av å samarbeide med:</p>
                        <?php if (!empty($sponsorImages)): ?>
                            <div class="row text-center">
                                <?php foreach ($sponsorImages as $sponsor): ?>
                                    <div class="col-6 mb-3">
                                        <div class="bg-light p-3 rounded">
                                            <img src="<?= htmlspecialchars($sponsor['logo_url']) ?>" 
                                                 alt="<?= htmlspecialchars($sponsor['name']) ?>" 
                                                 class="img-fluid" 
                                                 style="max-height: 60px; object-fit: contain;">
                                            <p class="mb-0 mt-2"><?= htmlspecialchars($sponsor['name']) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fas fa-handshake fa-3x mb-3"></i>
                                <p>Ingen sponsorer registrert ennå.</p>
                                <p>Bli den første!</p>
                            </div>
                        <?php endif; ?>
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
                <?php if (can_edit_inline() && !empty($cta_content['editor_html'])): ?>
                    <?= $cta_content['editor_html'] ?>
                <?php else: ?>
                    <h2 class="mb-4"><?= htmlspecialchars($cta_content['title']) ?></h2>
                    <p class="lead mb-4"><?= htmlspecialchars($cta_content['content']) ?></p>
                <?php endif; ?>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="<?= base_url('om-oss') ?>" class="btn btn-light btn-lg">
                        <i class="fas fa-users me-2"></i>Kontakt hovedkomiteen
                    </a>
                    <a href="<?= base_url('sponsor/presentasjon') ?>" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-handshake me-2"></i>Se våre sponsorer
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_footer(); ?>
