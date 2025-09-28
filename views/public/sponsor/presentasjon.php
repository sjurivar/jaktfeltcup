<?php
// Set page variables
$page_title = 'Våre Sponsorer - Jaktfeltcup';
$page_description = 'Se våre fantastiske sponsorer som støtter Jaktfeltcup.';
$current_page = 'sponsor_presentasjon';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';

// Get sponsor images
$sponsorImages = \Jaktfeltcup\Helpers\ImageHelper::getSponsorImages();
?>

<?php include_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-handshake me-2"></i>Våre Sponsorer</h3>
                </div>
                <div class="card-body">
                    <p class="lead">Vi er stolte av å samarbeide med disse fantastiske bedriftene som støtter Jaktfeltcup.</p>
                    
                    <?php if (!empty($sponsorImages)): ?>
                        <div class="row">
                            <?php foreach ($sponsorImages as $sponsor): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 text-center">
                                        <div class="card-body">
                                            <img src="<?= htmlspecialchars($sponsor['logo_url']) ?>" 
                                                 alt="<?= htmlspecialchars($sponsor['name']) ?>" 
                                                 class="img-fluid mb-3" 
                                                 style="max-height: 120px; object-fit: contain;"
                                                 onerror="console.log('Image failed to load: <?= htmlspecialchars($sponsor['logo_url']) ?>')"
                                                 onload="console.log('Image loaded successfully: <?= htmlspecialchars($sponsor['logo_url']) ?>')">
                                            <h5 class="card-title"><?= htmlspecialchars($sponsor['name']) ?></h5>
                                            <p class="card-text text-muted">
                                                Takk for støtten til Jaktfeltcup!
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-handshake fa-5x mb-4"></i>
                            <h4>Ingen sponsorer registrert ennå</h4>
                            <p class="lead">Bli den første sponsoren for Jaktfeltcup!</p>
                            <a href="<?= base_url('sponsor') ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-star me-2"></i>Bli sponsor
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <hr class="my-5">
                    
                    <div class="text-center">
                        <h4>Vil du bli sponsor?</h4>
                        <p class="lead">Kontakt oss for å diskutere muligheter for samarbeid.</p>
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            <a href="<?= base_url('sponsor') ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-star me-2"></i>Se sponsor-pakker
                            </a>
                            <a href="<?= base_url('sponsor/kontakt') ?>" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-envelope me-2"></i>Kontakt oss
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_footer(); ?>
