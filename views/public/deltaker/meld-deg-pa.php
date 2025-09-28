<?php
// Set page variables
$page_title = 'Meld deg på - Jaktfeltcup';
$page_description = 'Meld deg på som deltaker i Jaktfeltcup stevner.';
$current_page = 'deltaker_meld';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../../src/Core/Database.php';

// Initialize database connection
global $db_config;
$database = new Jaktfeltcup\Core\Database($db_config);

// Get upcoming competitions
$upcomingCompetitions = $database->queryAll(
    "SELECT * FROM jaktfelt_competitions 
     WHERE competition_date > NOW() 
     ORDER BY competition_date ASC"
);
?>

<?php include_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-user-plus me-2"></i>Meld deg på som deltaker</h3>
                </div>
                <div class="card-body">
                    <p class="lead">Velg hvilke stevner du ønsker å delta på og fyll ut påmeldingsskjemaet.</p>
                    
                    <?php if (!empty($upcomingCompetitions)): ?>
                        <div class="row">
                            <?php foreach ($upcomingCompetitions as $competition): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($competition['name']) ?></h5>
                                            <p class="card-text">
                                                <i class="fas fa-calendar me-2"></i>
                                                <?= date('d.m.Y', strtotime($competition['competition_date'])) ?>
                                            </p>
                                            <p class="card-text">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                <?= htmlspecialchars($competition['location']) ?>
                                            </p>
                                            <p class="card-text">
                                                <i class="fas fa-clock me-2"></i>
                                                <?= htmlspecialchars($competition['start_time'] ?? 'TBA') ?>
                                            </p>
                                            <div class="d-grid">
                                                <button class="btn btn-primary" onclick="selectCompetition(<?= $competition['id'] ?>, '<?= htmlspecialchars($competition['name']) ?>')">
                                                    <i class="fas fa-user-plus me-2"></i>Velg dette stevnet
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>
                            Ingen kommende stevner registrert ennå. Sjekk tilbake senere!
                        </div>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <!-- Registration Form -->
                    <div id="registrationForm" style="display: none;">
                        <h4 class="mb-3">Påmeldingsskjema</h4>
                        <form method="POST" action="<?= base_url('deltaker/meld-deg-pa') ?>">
                            <input type="hidden" id="selectedCompetitionId" name="competition_id">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">Fornavn *</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Etternavn *</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">E-postadresse *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Telefonnummer *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="age" class="form-label">Alder *</label>
                                    <input type="number" class="form-control" id="age" name="age" min="16" max="100" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="experience" class="form-label">Skyteerfaring *</label>
                                    <select class="form-select" id="experience" name="experience" required>
                                        <option value="">Velg erfaring</option>
                                        <option value="nybegynner">Nybegynner (0-1 år)</option>
                                        <option value="noe-erfaring">Noe erfaring (1-3 år)</option>
                                        <option value="erfaren">Erfaren (3-10 år)</option>
                                        <option value="meget-erfaren">Meget erfaren (10+ år)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="weapon" class="form-label">Skytevåpen *</label>
                                <input type="text" class="form-control" id="weapon" name="weapon" required 
                                       placeholder="F.eks. Remington 700, Tikka T3, etc.">
                            </div>
                            
                            <div class="mb-3">
                                <label for="license" class="form-label">Skytevåpenlisens nummer *</label>
                                <input type="text" class="form-control" id="license" name="license" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="club" class="form-label">Klubb/Organisasjon</label>
                                <input type="text" class="form-control" id="club" name="club" 
                                       placeholder="F.eks. Oslo Skytterlag">
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Spesielle behov eller merknader</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="F.eks. allergier, tilgjengelighet, etc."></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="insurance" name="insurance" value="1" required>
                                    <label class="form-check-label" for="insurance">
                                        Jeg bekrefter at jeg har gyldig forsikring som dekker skyteidrett *
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rules" name="rules" value="1" required>
                                    <label class="form-check-label" for="rules">
                                        Jeg har lest og godtar <a href="<?= base_url('deltaker/regler') ?>" target="_blank">reglene</a> for Jaktfeltcup *
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" value="1" required>
                                    <label class="form-check-label" for="privacy">
                                        Jeg godtar <a href="<?= base_url('about') ?>" target="_blank">personvernreglene</a> *
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Send påmelding
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectCompetition(competitionId, competitionName) {
    document.getElementById('selectedCompetitionId').value = competitionId;
    document.getElementById('registrationForm').style.display = 'block';
    
    // Scroll to form
    document.getElementById('registrationForm').scrollIntoView({ behavior: 'smooth' });
    
    // Show selected competition
    const form = document.getElementById('registrationForm');
    const title = form.querySelector('h4');
    title.innerHTML = `Påmeldingsskjema - ${competitionName}`;
}
</script>

<?php include_footer(); ?>
