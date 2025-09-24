<?php
// Set page variables
$page_title = 'Sammenlagt';
$page_description = 'Se sammenlagtresultater';
$current_page = 'standings';
?>

<?php include_header(); ?>

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
                            // Check if season parameter is provided in URL
                            $seasonId = isset($_GET['season']) ? (int)$_GET['season'] : null;
                            
                            if (!$seasonId) {
                                $currentSeason = $dataService->findOne('seasons', ['is_active' => true]);
                                $seasonId = $currentSeason ? $currentSeason['id'] : 1;
                            }
                            
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
                                // Check if season parameter is provided in URL
                                $seasonId = isset($_GET['season']) ? (int)$_GET['season'] : null;
                                
                                if (!$seasonId) {
                                    $currentSeason = $dataService->queryOne("SELECT * FROM jaktfelt_seasons WHERE is_active = 1 ORDER BY year DESC LIMIT 1");
                                    $seasonId = $currentSeason ? $currentSeason['id'] : 1;
                                }
                                
                                $standings = $dataService->query(
                                    "SELECT u.id, u.first_name, u.last_name, 
                                            SUM(r.points_awarded) as total_points,
                                            COUNT(r.id) as competitions_entered,
                                            AVG(r.score) as avg_score
                                     FROM jaktfelt_users u
                                     LEFT JOIN jaktfelt_results r ON u.id = r.user_id
                                     LEFT JOIN jaktfelt_competitions c ON r.competition_id = c.id
                                     LEFT JOIN jaktfelt_seasons s ON c.season_id = s.id
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
                                echo '<a href="' . base_url('admin/database') . '" class="btn btn-primary">Database Admin</a>';
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
                        if ($app_config['data_source'] === 'json') {
                            $seasons = $dataService->getAll('seasons');
                            usort($seasons, function($a, $b) { return $b['year'] - $a['year']; });
                        } else {
                            $seasons = $dataService->query("SELECT * FROM jaktfelt_seasons ORDER BY year DESC");
                        }
                        
                        foreach ($seasons as $season) {
                            echo '<a href="' . base_url('standings?season=' . $season['id']) . '" class="d-block mb-2">';
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
                        if ($app_config['data_source'] === 'json') {
                            $pointSystem = $dataService->findOne('point_systems', ['is_active' => true]);
                        } else {
                            $pointSystem = $dataService->queryOne(
                                "SELECT ps.name, ps.description 
                                 FROM jaktfelt_point_systems ps
                                 JOIN jaktfelt_season_point_systems sps ON ps.id = sps.point_system_id
                                 WHERE sps.season_id = ? AND ps.is_active = 1",
                                [$seasonId]
                            );
                        }
                        
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

<?php include_footer(); ?>
