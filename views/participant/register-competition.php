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
            <a class="navbar-brand" href="/">
                <i class="fas fa-bullseye me-2"></i>Jaktfeltcup
            </a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="/participant/dashboard">Min side</a>
                <a class="nav-link" href="/competitions">Stevner</a>
                <a class="nav-link" href="/results">Resultater</a>
            </div>
            <div class="navbar-nav">
                <a class="nav-link" href="/logout">Logg ut</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Meld deg på stevne</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $_SESSION['error'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <!-- Competition Info -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title"><?= htmlspecialchars($competition['name']) ?></h6>
                                <p class="card-text text-muted"><?= htmlspecialchars($competition['description']) ?></p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?= date('d.m.Y', strtotime($competition['competition_date'])) ?>
                                        </small>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <?= htmlspecialchars($competition['location']) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="/participant/register/<?= $competition['id'] ?>">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Velg kategori *</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Velg kategori</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                            <?php if ($category['description']): ?>
                                                - <?= htmlspecialchars($category['description']) ?>
                                            <?php endif; ?>
                                            <?php if ($category['min_age'] || $category['max_age']): ?>
                                                (Alder: 
                                                <?php if ($category['min_age']) echo $category['min_age'] . '+'; ?>
                                                <?php if ($category['min_age'] && $category['max_age']) echo ' - '; ?>
                                                <?php if ($category['max_age']) echo $category['max_age']; ?>
                                                )
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notater (valgfritt)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Eventuelle notater til arrangøren..."></textarea>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Ved påmelding godtar du stevnereglementet og bekrefter at opplysningene er korrekte.
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="/competitions" class="btn btn-outline-secondary">Avbryt</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check me-2"></i>Meld meg på
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
