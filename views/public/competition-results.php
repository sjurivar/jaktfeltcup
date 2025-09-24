<?php
// Set page variables
$page_title = 'Stevneresultater';
$page_description = 'Se resultater fra et spesifikt stevne';
$current_page = 'results';
?>

<?php include_header(); ?>

    <div class="container mt-4">
        <?php
        $dataService = getDataService();
        
        if ($app_config['data_source'] === 'json') {
            // Use JSON data
            $competition = $dataService->getById('competitions', $competitionId);
            
            if (!$competition) {
                echo '<div class="alert alert-danger">Stevne ikke funnet</div>';
                exit;
            }
            
            // Get results for this competition
            $results = $dataService->find('results', ['competition_id' => $competitionId]);
            $users = $dataService->getAll('users');
            $categories = $dataService->getAll('categories');
            
            // Add user and category names to results
            foreach ($results as &$result) {
                $user = $dataService->getById('users', $result['user_id']);
                $category = $dataService->getById('categories', $result['category_id']);
                
                $result['user_name'] = $user ? $user['first_name'] . ' ' . $user['last_name'] : 'Ukjent';
                $result['category_name'] = $category ? $category['name'] : 'Ukjent';
            }
            
            // Sort by category, then by score
            usort($results, function($a, $b) {
                if ($a['category_name'] == $b['category_name']) {
                    return $b['score'] - $a['score'];
                }
                return strcmp($a['category_name'], $b['category_name']);
            });
            
        } else {
            // Use database
            try {
                $competition = $dataService->queryOne(
                    "SELECT c.*, s.name as season_name 
                     FROM jaktfelt_competitions c 
                     JOIN jaktfelt_seasons s ON c.season_id = s.id 
                     WHERE c.id = ? AND (c.status = 'upcoming' OR c.status = 'completed')",
                    [$competitionId]
                );
                
                if (!$competition) {
                    echo '<div class="alert alert-danger">Stevne ikke funnet</div>';
                    exit;
                }
                
                $results = $dataService->query(
                    "SELECT r.*, u.first_name, u.last_name, cat.name as category_name,
                            CONCAT(u.first_name, ' ', u.last_name) as user_name
                     FROM jaktfelt_results r
                     JOIN jaktfelt_users u ON r.user_id = u.id
                     JOIN jaktfelt_categories cat ON r.category_id = cat.id
                     WHERE r.competition_id = ?
                     ORDER BY cat.name, r.score DESC",
                    [$competitionId]
                );
                
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Feil ved henting av data: ' . $e->getMessage() . '</div>';
                exit;
            }
        }
        ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><?= htmlspecialchars($competition['name']) ?></h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar me-1"></i>
                            <?= date('d.m.Y', strtotime($competition['date'])) ?>
                            <i class="fas fa-map-marker-alt ms-3 me-1"></i>
                            <?= htmlspecialchars($competition['location']) ?>
                        </p>
                    </div>
                    <div class="card-body">
                        <?php if (empty($results)): ?>
                            <p class="text-muted">Ingen resultater registrert for dette stevnet ennå.</p>
                        <?php else: ?>
                            <?php
                            $currentCategory = '';
                            foreach ($results as $result):
                                if ($result['category_name'] !== $currentCategory):
                                    if ($currentCategory !== ''):
                                        echo '</tbody></table></div>';
                                    endif;
                                    $currentCategory = $result['category_name'];
                                    echo '<div class="mb-4">';
                                    echo '<h5>' . htmlspecialchars($currentCategory) . '</h5>';
                                    echo '<div class="table-responsive">';
                                    echo '<table class="table table-striped table-sm">';
                                    echo '<thead class="table-dark">';
                                    echo '<tr>';
                                    echo '<th>Plass</th>';
                                    echo '<th>Navn</th>';
                                    echo '<th>Poeng</th>';
                                    echo '<th>Poeng tildelt</th>';
                                    echo '</tr>';
                                    echo '</thead>';
                                    echo '<tbody>';
                                endif;
                                
                                $medalClass = '';
                                if ($result['position'] == 1) $medalClass = 'text-warning';
                                elseif ($result['position'] == 2) $medalClass = 'text-secondary';
                                elseif ($result['position'] == 3) $medalClass = 'text-warning';
                                
                                echo '<tr>';
                                echo '<td>';
                                if ($result['position']) {
                                    echo '<strong class="' . $medalClass . '">' . $result['position'] . '</strong>';
                                } else {
                                    echo '<span class="text-muted">-</span>';
                                }
                                echo '</td>';
                                echo '<td>' . htmlspecialchars($result['user_name']) . '</td>';
                                echo '<td><strong>' . $result['score'] . '</strong></td>';
                                echo '<td>' . $result['points_awarded'] . '</td>';
                                echo '</tr>';
                            endforeach;
                            
                            if ($currentCategory !== ''):
                                echo '</tbody></table></div></div>';
                            endif;
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Stevneinfo</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Beskrivelse:</strong><br>
                        <?= htmlspecialchars($competition['description']) ?></p>
                        
                        <p><strong>Arrangør:</strong><br>
                        <?php
                        if ($app_config['data_source'] === 'json') {
                            $organizer = $dataService->getById('users', $competition['organizer_id']);
                            echo $organizer ? htmlspecialchars($organizer['first_name'] . ' ' . $organizer['last_name']) : 'Ukjent';
                        } else {
                            echo 'Arrangør';
                        }
                        ?>
                        </p>
                        
                        <p><strong>Status:</strong><br>
                        <?php if ($competition['status'] === 'completed'): ?>
                            <span class="badge bg-success">Fullført</span>
                        <?php elseif ($competition['status'] === 'upcoming'): ?>
                            <span class="badge bg-warning">Kommende</span>
                        <?php else: ?>
                            <span class="badge bg-info"><?= htmlspecialchars($competition['status']) ?></span>
                        <?php endif; ?>
                        </p>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Handlinger</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('results') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Tilbake til resultater
                            </a>
                            <a href="<?= base_url('competitions') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>Alle stevner
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_footer(); ?>
