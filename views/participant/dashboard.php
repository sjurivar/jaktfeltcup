<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Jaktfeltcup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-bullseye me-2"></i>Jaktfeltcup
            </a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="<?= base_url() ?>">Hjem</a>
                <a class="nav-link" href="<?= base_url('results') ?>">Resultater</a>
                <a class="nav-link" href="<?= base_url('standings') ?>">Sammenlagt</a>
                <a class="nav-link" href="<?= base_url('competitions') ?>">Stevner</a>
            </div>
            <div class="navbar-nav">
                <span class="navbar-text me-3">Hei, <?= htmlspecialchars($user['first_name']) ?>!</span>
                <a class="nav-link" href="<?= base_url('participant/profile') ?>">Min profil</a>
                <a class="nav-link" href="<?= base_url('logout') ?>">Logg ut</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <h1 class="mb-4">Min side</h1>
                
                <!-- Recent Registrations -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Mine påmeldinger</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($registrations)): ?>
                            <p class="text-muted">Du har ingen påmeldinger ennå.</p>
                            <a href="/jaktfeltcup/competitions" class="btn btn-primary">Se tilgjengelige stevner</a>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Stevne</th>
                                            <th>Dato</th>
                                            <th>Kategori</th>
                                            <th>Status</th>
                                            <th>Handling</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($registrations as $registration): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($registration['competition_name']) ?></td>
                                                <td><?= date('d.m.Y', strtotime($registration['competition_date'])) ?></td>
                                                <td><?= htmlspecialchars($registration['category_name']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $registration['status'] === 'confirmed' ? 'success' : 'warning' ?>">
                                                        <?= ucfirst($registration['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (strtotime($registration['competition_date']) > time()): ?>
                                                        <a href="/participant/unregister/<?= $registration['competition_id'] ?>" 
                                                           class="btn btn-sm btn-outline-danger"
                                                           onclick="return confirm('Er du sikker på at du vil melde deg av?')">
                                                            Meld av
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Results -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Mine resultater</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($results)): ?>
                            <p class="text-muted">Du har ingen resultater ennå.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Stevne</th>
                                            <th>Dato</th>
                                            <th>Kategori</th>
                                            <th>Poeng</th>
                                            <th>Plassering</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($results as $result): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($result['competition_name']) ?></td>
                                                <td><?= date('d.m.Y', strtotime($result['competition_date'])) ?></td>
                                                <td><?= htmlspecialchars($result['category_name']) ?></td>
                                                <td><strong><?= $result['score'] ?></strong></td>
                                                <td>
                                                    <?php if ($result['position']): ?>
                                                        <span class="badge bg-primary"><?= $result['position'] ?>. plass</span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Hurtighandlinger</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="/jaktfeltcup/competitions" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Meld deg på stevne
                            </a>
                            <a href="/jaktfeltcup/participant/registrations" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>Mine påmeldinger
                            </a>
                            <a href="/jaktfeltcup/participant/results" class="btn btn-outline-primary">
                                <i class="fas fa-chart-line me-2"></i>Mine resultater
                            </a>
                            <a href="/jaktfeltcup/participant/profile" class="btn btn-outline-secondary">
                                <i class="fas fa-user-edit me-2"></i>Rediger profil
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Statistikk</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-primary"><?= count($registrations) ?></h4>
                                <small class="text-muted">Påmeldinger</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success"><?= count($results) ?></h4>
                                <small class="text-muted">Resultater</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
