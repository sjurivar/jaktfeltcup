<?php
// Set page variables
$page_title = 'Resultater';
$page_description = 'Se resultater fra alle stevner';
$current_page = 'results';
?>

<?php include_header(); ?>

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
                                return $c['status'] == 'upcoming' || $c['status'] == 'completed';
                            });
                            
                            if (empty($competitions)) {
                                echo '<p class="text-muted">Ingen resultater tilgjengelig ennå.</p>';
                            } else {
                                // Sort by competition date
                                usort($competitions, function($a, $b) {
                                    return strtotime($b['date']) - strtotime($a['date']);
                                });
                                
                                foreach ($competitions as $competition) {
                                    echo '<div class="mb-3">';
                                    echo '<h6>' . htmlspecialchars($competition['name']) . '</h6>';
                                    echo '<p class="text-muted small">';
                                    echo 'Dato: ' . date('d.m.Y', strtotime($competition['date'])) . ' | ';
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
                                $competitions = $dataService->query(
                                    "SELECT c.*, s.name as season_name,
                                            COUNT(r.id) as result_count
                                     FROM jaktfelt_competitions c
                                     JOIN jaktfelt_seasons s ON c.season_id = s.id
                                     LEFT JOIN jaktfelt_results r ON c.id = r.competition_id
                                     WHERE c.status = 'upcoming' OR c.status = 'completed'
                                     GROUP BY c.id
                                     ORDER BY c.date DESC"
                                );
                                
                                if (empty($competitions)) {
                                    echo '<p class="text-muted">Ingen resultater tilgjengelig ennå.</p>';
                                } else {
                                    foreach ($competitions as $competition) {
                                        echo '<div class="mb-3">';
                                        echo '<h6>' . htmlspecialchars($competition['name']) . '</h6>';
                                        echo '<p class="text-muted small">';
                                        echo 'Dato: ' . date('d.m.Y', strtotime($competition['date'])) . ' | ';
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
                        try {
                            $seasons = $dataService->query("SELECT * FROM jaktfelt_seasons ORDER BY year DESC");
                            foreach ($seasons as $season) {
                                echo '<a href="' . base_url('results?season=' . $season['id']) . '" class="d-block mb-2">';
                                echo htmlspecialchars($season['name']);
                                if ($season['is_active']) {
                                    echo ' <span class="badge bg-success">Aktiv</span>';
                                }
                                echo '</a>';
                            }
                        } catch (Exception $e) {
                            echo '<p class="text-muted">Ingen sesonger tilgjengelig.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_footer(); ?>
