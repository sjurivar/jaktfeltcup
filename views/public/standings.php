<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sammenlagt - Jaktfeltcup</title>
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
                <a class="nav-link" href="<?= base_url('results') ?>">Resultater</a>
                <a class="nav-link active" href="<?= base_url('standings') ?>">Sammenlagt</a>
                <a class="nav-link" href="<?= base_url('competitions') ?>">Stevner</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Sammenlagt</h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Cup-sammenlagt</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $dataService = getDataService();
                        
                        if ($app_config['data_source'] === 'json') {
                            // Use JSON data
                            $currentSeason = $dataService->findOne('seasons', ['is_active' => true]);
                            $seasonId = $currentSeason ? $currentSeason['id'] : 1;
                            
                            $standings = $dataService->getStandings($seasonId);
                            
                            if (empty($standings)) {
                                echo '<p class="text-muted">Ingen resultater i sammenlagt ennå.</p>';
                            } else {
                                echo '<div class="table-responsive">';
                                echo '<table class="table table-striped">';
                                echo '<thead>';
                                echo '<tr>';
                                echo '<th>Plass</th>';
                                echo '<th>Navn</th>';
                                echo '<th>Poeng</th>';
                                echo '<th>Stevner</th>';
                                echo '<th>Snitt</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';
                                
                                $position = 1;
                                foreach ($standings as $standing) {
                                    $medalClass = '';
                                    if ($position == 1) $medalClass = 'text-warning';
                                    elseif ($position == 2) $medalClass = 'text-secondary';
                                    elseif ($position == 3) $medalClass = 'text-warning';
                                    
                                    echo '<tr>';
                                    echo '<td><strong class="' . $medalClass . '">' . $position . '</strong></td>';
                                    echo '<td>' . htmlspecialchars($standing['first_name'] . ' ' . $standing['last_name']) . '</td>';
                                    echo '<td><strong>' . $standing['total_points'] . '</strong></td>';
                                    echo '<td>' . $standing['competitions_entered'] . '</td>';
                                    echo '<td>' . round($standing['avg_score'], 1) . '</td>';
                                    echo '</tr>';
                                    $position++;
                                }
                                
                                echo '</tbody>';
                                echo '</table>';
                                echo '</div>';
                            }
                        } else {
                            // Use database
                            try {
                                $currentSeason = $database->queryOne("SELECT * FROM seasons WHERE is_active = 1 ORDER BY year DESC LIMIT 1");
                                $seasonId = $currentSeason ? $currentSeason['id'] : 1;
                                
                                $standings = $database->query(
                                    "SELECT u.id, u.first_name, u.last_name, 
                                            SUM(r.points_awarded) as total_points,
                                            COUNT(r.id) as competitions_entered,
                                            AVG(r.score) as avg_score
                                     FROM users u
                                     LEFT JOIN results r ON u.id = r.user_id
                                     LEFT JOIN competitions c ON r.competition_id = c.id
                                     LEFT JOIN seasons s ON c.season_id = s.id
                                     WHERE s.id = ? AND r.points_awarded > 0
                                     GROUP BY u.id, u.first_name, u.last_name
                                     ORDER BY total_points DESC, competitions_entered DESC",
                                    [$seasonId]
                                );
                                
                                if (empty($standings)) {
                                    echo '<p class="text-muted">Ingen resultater i sammenlagt ennå.</p>';
                                } else {
                                    echo '<div class="table-responsive">';
                                    echo '<table class="table table-striped">';
                                    echo '<thead>';
                                    echo '<tr>';
                                    echo '<th>Plass</th>';
                                    echo '<th>Navn</th>';
                                    echo '<th>Poeng</th>';
                                    echo '<th>Stevner</th>';
                                    echo '<th>Snitt</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';
                                    
                                    $position = 1;
                                    foreach ($standings as $standing) {
                                        $medalClass = '';
                                        if ($position == 1) $medalClass = 'text-warning';
                                        elseif ($position == 2) $medalClass = 'text-secondary';
                                        elseif ($position == 3) $medalClass = 'text-warning';
                                        
                                        echo '<tr>';
                                        echo '<td><strong class="' . $medalClass . '">' . $position . '</strong></td>';
                                        echo '<td>' . htmlspecialchars($standing['first_name'] . ' ' . $standing['last_name']) . '</td>';
                                        echo '<td><strong>' . $standing['total_points'] . '</strong></td>';
                                        echo '<td>' . $standing['competitions_entered'] . '</td>';
                                        echo '<td>' . round($standing['avg_score'], 1) . '</td>';
                                        echo '</tr>';
                                        $position++;
                                    }
                                    
                                    echo '</tbody>';
                                    echo '</table>';
                                    echo '</div>';
                                }
                            } catch (Exception $e) {
                                echo '<div class="alert alert-info">';
                                echo '<h6>Database ikke satt opp ennå</h6>';
                                echo '<p>For å se sammenlagt, må du først sette opp databasen:</p>';
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
                            echo '<a href="/standings?season=' . $season['id'] . '" class="d-block mb-2">';
                            echo htmlspecialchars($season['name']);
                            if ($season['is_active']) {
                                echo ' <span class="badge bg-success">Aktiv</span>';
                            }
                            echo '</a>';
                        }
                        ?>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Poengsystem</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $pointSystem = $database->queryOne(
                            "SELECT ps.name, ps.description 
                             FROM point_systems ps
                             JOIN season_point_systems sps ON ps.id = sps.point_system_id
                             WHERE sps.season_id = ? AND ps.is_active = 1",
                            [$seasonId]
                        );
                        
                        if ($pointSystem) {
                            echo '<h6>' . htmlspecialchars($pointSystem['name']) . '</h6>';
                            echo '<p class="small text-muted">' . htmlspecialchars($pointSystem['description']) . '</p>';
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
