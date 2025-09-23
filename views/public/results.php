<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultater - Jaktfeltcup</title>
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
            <div class="navbar-nav">
                <a class="nav-link" href="<?= base_url() ?>">Hjem</a>
                <a class="nav-link active" href="<?= base_url('results') ?>">Resultater</a>
                <a class="nav-link" href="<?= base_url('standings') ?>">Sammenlagt</a>
                <a class="nav-link" href="<?= base_url('competitions') ?>">Stevner</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Resultater</h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Stevneresultater</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $dataService = getDataService();
                        
                        if ($app_config['data_source'] === 'json') {
                            // Use JSON data
                            $competitions = $dataService->getCompetitionsWithResults();
                            $competitions = array_filter($competitions, function($c) {
                                return $c['is_published'] == 1;
                            });
                            
                            if (empty($competitions)) {
                                echo '<p class="text-muted">Ingen resultater tilgjengelig ennå.</p>';
                            } else {
                                // Sort by competition date
                                usort($competitions, function($a, $b) {
                                    return strtotime($b['competition_date']) - strtotime($a['competition_date']);
                                });
                                
                                foreach ($competitions as $competition) {
                                    echo '<div class="mb-3">';
                                    echo '<h6>' . htmlspecialchars($competition['name']) . '</h6>';
                                    echo '<p class="text-muted small">';
                                    echo 'Dato: ' . date('d.m.Y', strtotime($competition['competition_date'])) . ' | ';
                                    echo 'Sted: ' . htmlspecialchars($competition['location']) . ' | ';
                                    echo 'Resultater: ' . $competition['result_count'];
                                    echo '</p>';
                                    echo '<a href="' . base_url('results/' . $competition['id']) . '" class="btn btn-sm btn-outline-primary">Se resultater</a>';
                                    echo '</div>';
                                }
                            }
                        } else {
                            // Use database
                            try {
                                $competitions = $database->query(
                                    "SELECT c.*, s.name as season_name,
                                            COUNT(r.id) as result_count
                                     FROM competitions c
                                     JOIN seasons s ON c.season_id = s.id
                                     LEFT JOIN results r ON c.id = r.competition_id
                                     WHERE c.is_published = 1
                                     GROUP BY c.id
                                     ORDER BY c.competition_date DESC"
                                );
                                
                                if (empty($competitions)) {
                                    echo '<p class="text-muted">Ingen resultater tilgjengelig ennå.</p>';
                                } else {
                                    foreach ($competitions as $competition) {
                                        echo '<div class="mb-3">';
                                        echo '<h6>' . htmlspecialchars($competition['name']) . '</h6>';
                                        echo '<p class="text-muted small">';
                                        echo 'Dato: ' . date('d.m.Y', strtotime($competition['competition_date'])) . ' | ';
                                        echo 'Sted: ' . htmlspecialchars($competition['location']) . ' | ';
                                        echo 'Resultater: ' . $competition['result_count'];
                                        echo '</p>';
                                        echo '<a href="' . base_url('results/' . $competition['id']) . '" class="btn btn-sm btn-outline-primary">Se resultater</a>';
                                        echo '</div>';
                                    }
                                }
                            } catch (Exception $e) {
                                echo '<div class="alert alert-info">';
                                echo '<h6>Database ikke satt opp ennå</h6>';
                                echo '<p>For å se resultater, må du først sette opp databasen:</p>';
                                echo '<a href="' . base_url('setup_database.php') . '" class="btn btn-primary">Sett opp database</a>';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Sesonger</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $seasons = $database->query("SELECT * FROM seasons ORDER BY year DESC");
                        foreach ($seasons as $season) {
                            echo '<a href="/results?season=' . $season['id'] . '" class="d-block mb-2">';
                            echo htmlspecialchars($season['name']);
                            if ($season['is_active']) {
                                echo ' <span class="badge bg-success">Aktiv</span>';
                            }
                            echo '</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
