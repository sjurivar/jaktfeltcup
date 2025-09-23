<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-bullseye me-2"></i>Jaktfeltcup
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('results') ?>">Resultater</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('standings') ?>">Sammenlagt</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('competitions') ?>">Stevner</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="navbar-text me-3">Hei, <?= htmlspecialchars($_SESSION['user_name']) ?>!</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('participant/dashboard') ?>">Min side</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('logout') ?>">Logg ut</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login') ?>">Logg inn</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>">Registrer</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

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

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Jaktfeltcup</h5>
                    <p class="text-muted">Administrasjonssystem for skyteøvelse</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">&copy; 2024 Jaktfeltcup. Alle rettigheter forbeholdt.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
