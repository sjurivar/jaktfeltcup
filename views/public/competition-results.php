<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stevneresultater - Jaktfeltcup</title>
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
                <a class="nav-link" href="<?= base_url('standings') ?>">Sammenlagt</a>
                <a class="nav-link" href="<?= base_url('competitions') ?>">Stevner</a>
            </div>
        </div>
    </nav>

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
                $competition = $database->queryOne(
                    "SELECT c.*, s.name as season_name 
                     FROM competitions c 
                     JOIN seasons s ON c.season_id = s.id 
                     WHERE c.id = ? AND c.is_published = 1",
                    [$competitionId]
                );
                
                if (!$competition) {
                    echo '<div class="alert alert-danger">Stevne ikke funnet</div>';
                    exit;
                }
                
                $results = $database->query(
                    "SELECT r.*, u.first_name, u.last_name, cat.name as category_name
                     FROM results r
                     JOIN users u ON r.user_id = u.id
                     JOIN categories cat ON r.category_id = cat.id
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
                            <?= date('d.m.Y', strtotime($competition['competition_date'])) ?>
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
                        <?php if ($competition['is_locked']): ?>
                            <span class="badge bg-success">Låst</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Åpen for redigering</span>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
