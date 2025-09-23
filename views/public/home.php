<?php
// Set page variables
$page_title = 'Velkommen til Jaktfeltcup';
$page_description = 'Administrasjonssystem for skyteøvelse';
$current_page = 'home';
?>

<?php include_header(); ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Velkommen til Jaktfeltcup</h1>
            <p class="lead mb-4">Administrasjonssystem for skyteøvelse</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="<?= base_url('results') ?>" class="btn btn-light btn-lg me-md-2">
                    <i class="fas fa-chart-line me-2"></i>Se resultater
                </a>
                <a href="<?= base_url('register') ?>" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Bli med
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">Funksjoner</h2>
                    <p class="text-muted">Alt du trenger for å administrere en skytekonkurranse</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-primary bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-users text-white fs-4"></i>
                            </div>
                            <h5 class="card-title">Deltakeradministrasjon</h5>
                            <p class="card-text text-muted">Enkelt påmelding og administrasjon av deltakere</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-trophy text-white fs-4"></i>
                            </div>
                            <h5 class="card-title">Resultathåndtering</h5>
                            <p class="card-text text-muted">Registrer og vis resultater i sanntid</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="bg-info bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-mobile-alt text-white fs-4"></i>
                            </div>
                            <h5 class="card-title">Offline-funksjonalitet</h5>
                            <p class="card-text text-muted">Registrer resultater uten internett</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="mb-3">
                        <i class="fas fa-calendar-alt text-primary fs-1"></i>
                    </div>
                    <h3 class="fw-bold">4 Stevner</h3>
                    <p class="text-muted">I løpet av sesongen</p>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <i class="fas fa-user-friends text-success fs-1"></i>
                    </div>
                    <h3 class="fw-bold">Fleksibel</h3>
                    <p class="text-muted">Støtter flere kategorier</p>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <i class="fas fa-chart-bar text-info fs-1"></i>
                    </div>
                    <h3 class="fw-bold">Sammenlagt</h3>
                    <p class="text-muted">Automatisk poengberegning</p>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <i class="fas fa-bell text-warning fs-1"></i>
                    </div>
                    <h3 class="fw-bold">Varslinger</h5>
                    <p class="text-muted">E-post og SMS</p>
                </div>
            </div>
        </div>
    </section>

<?php include_footer(); ?>
