<?php
// Set page variables
$page_title = 'Stevner';
$page_description = 'Se alle stevner';
$current_page = 'competitions';
?>

<?php include_header(); ?>

    <div class="container mt-4">
        <h1 class="mb-4">Stevner</h1>
        
        <div class="row">
            <div class="col-md-8">
                <?php
                try {
                    // Get current season
                    $currentSeason = $database->queryOne("SELECT * FROM jaktfelt_seasons WHERE is_active = 1 ORDER BY year DESC LIMIT 1");
                    $seasonId = $currentSeason ? $currentSeason['id'] : 1;
                    
                    // Get competitions
                    $competitions = $database->query(
                        "SELECT c.*, s.name as season_name,
                                COUNT(reg.id) as registered_count,
                                u.first_name, u.last_name
                         FROM jaktfelt_competitions c
                         JOIN jaktfelt_seasons s ON c.season_id = s.id
                         LEFT JOIN jaktfelt_registrations reg ON c.id = reg.competition_id AND reg.status = 'confirmed'
                         LEFT JOIN jaktfelt_users u ON c.organizer_id = u.id
                         WHERE s.id = ? AND c.is_published = 1
                         GROUP BY c.id
                         ORDER BY c.competition_date ASC",
                        [$seasonId]
                    );
                    
                    if (empty($competitions)) {
                        echo '<div class="alert alert-info">Ingen stevner publisert ennå.</div>';
                    } else {
                        foreach ($competitions as $competition) {
                            $isUpcoming = strtotime($competition['competition_date']) > time();
                            $isRegistrationOpen = strtotime($competition['registration_start']) <= time() && 
                                                strtotime($competition['registration_end']) >= time();
                            
                            echo '<div class="card mb-3">';
                            echo '<div class="card-body">';
                            echo '<div class="row">';
                            echo '<div class="col-md-8">';
                            echo '<h5 class="card-title">' . htmlspecialchars($competition['name']) . '</h5>';
                            echo '<p class="text-muted">' . htmlspecialchars($competition['description']) . '</p>';
                            echo '<div class="row">';
                            echo '<div class="col-sm-6">';
                            echo '<small class="text-muted">';
                            echo '<i class="fas fa-calendar me-1"></i> ';
                            echo date('d.m.Y', strtotime($competition['competition_date'])) . '<br>';
                            echo '<i class="fas fa-map-marker-alt me-1"></i> ';
                            echo htmlspecialchars($competition['location']) . '<br>';
                            echo '<i class="fas fa-user me-1"></i> ';
                            echo 'Arrangør: ' . htmlspecialchars($competition['first_name'] . ' ' . $competition['last_name']);
                            echo '</small>';
                            echo '</div>';
                            echo '<div class="col-sm-6">';
                            echo '<small class="text-muted">';
                            echo '<i class="fas fa-users me-1"></i> ';
                            echo 'Påmeldt: ' . $competition['registered_count'];
                            if ($competition['max_participants']) {
                                echo ' / ' . $competition['max_participants'];
                            }
                            echo '<br>';
                            echo '<i class="fas fa-clock me-1"></i> ';
                            echo 'Påmelding: ' . date('d.m.Y', strtotime($competition['registration_start'])) . ' - ' . 
                                 date('d.m.Y', strtotime($competition['registration_end']));
                            echo '</small>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="col-md-4 text-end">';
                            
                            if ($isUpcoming) {
                                if ($isRegistrationOpen) {
                                    echo '<span class="badge bg-success mb-2">Påmelding åpen</span><br>';
                                } else {
                                    echo '<span class="badge bg-warning mb-2">Påmelding stengt</span><br>';
                                }
                            } else {
                                echo '<span class="badge bg-secondary mb-2">Avholdt</span><br>';
                            }
                            
                            echo '<a href="/results/' . $competition['id'] . '" class="btn btn-outline-primary btn-sm">Se resultater</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                } catch (Exception $e) {
                    echo '<div class="alert alert-info">';
                    echo '<h6>Database ikke satt opp ennå</h6>';
                    echo '<p>For å se stevner, må du først sette opp databasen:</p>';
                    echo '<a href="/jaktfeltcup/setup_database.php" class="btn btn-primary">Sett opp database</a>';
                    echo '</div>';
                }
                ?>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Sesonger</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $seasons = $database->query("SELECT * FROM jaktfelt_seasons ORDER BY year DESC");
                        foreach ($seasons as $season) {
                            echo '<a href="/competitions?season=' . $season['id'] . '" class="d-block mb-2">';
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
                        <h5 class="mb-0">Kategorier</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $categories = $database->query("SELECT * FROM jaktfelt_categories WHERE is_active = 1 ORDER BY name");
                        foreach ($categories as $category) {
                            echo '<div class="mb-2">';
                            echo '<strong>' . htmlspecialchars($category['name']) . '</strong>';
                            if ($category['description']) {
                                echo '<br><small class="text-muted">' . htmlspecialchars($category['description']) . '</small>';
                            }
                            if ($category['min_age'] || $category['max_age']) {
                                echo '<br><small class="text-muted">Alder: ';
                                if ($category['min_age']) echo $category['min_age'] . '+';
                                if ($category['min_age'] && $category['max_age']) echo ' - ';
                                if ($category['max_age']) echo $category['max_age'];
                                echo '</small>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_footer(); ?>
