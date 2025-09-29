<?php
// Set page variables
$page_title = 'Om Jaktfeltcup';
$page_description = 'Informasjon om Jaktfeltcup web-applikasjonen';
$current_page = 'about';
?>

<?php include_header(); ?>

    <div class="container mt-4">
        <h1 class="mb-4">Om Jaktfeltcup</h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Jaktfeltcup Web-applikasjon</h5>
                    </div>
                    <div class="card-body">
                        <p class="lead">En moderne web-applikasjon for administrasjon av jaktfeltcup med skyteøvelser.</p>
                        
                        <h6>Funksjoner:</h6>
                        <ul>
                            <li><strong>Publikum:</strong> Se resultater fra enkeltstevner og sammenlagt</li>
                            <li><strong>Deltakere:</strong> Opprett bruker, meld deg på stevner, rediger opplysninger</li>
                            <li><strong>Stevnearrangører:</strong> Opprett stevner, legg inn resultater, administrer deltakere</li>
                            <li><strong>Administratorer:</strong> Tildel roller og rediger alle data</li>
                        </ul>
                        
                        <h6>Teknisk informasjon:</h6>
                        <ul>
                            <li><strong>Backend:</strong> PHP 8.2+</li>
                            <li><strong>Database:</strong> MySQL</li>
                            <li><strong>Frontend:</strong> Bootstrap 5, HTML5, CSS3</li>
                            <li><strong>Deployment:</strong> GitHub Actions + FTP</li>
                            <li><strong>Utvikling:</strong> AI-assistert kodegenerering og kvalitetssikring</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Versjonsinformasjon</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Versjon:</strong><br>
                            <span class="badge bg-primary fs-6">v0.1.0-beta</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Status:</strong><br>
                            <span class="badge bg-warning">Beta</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Sist oppdatert:</strong><br>
                            <small class="text-muted"><?= date('d.m.Y H:i') ?></small>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Git Commit:</strong><br>
                            <small class="text-muted font-monospace"><?= substr(exec('git rev-parse HEAD'), 0, 8) ?: 'N/A' ?></small>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Git Branch:</strong><br>
                            <small class="text-muted"><?= exec('git branch --show-current') ?: 'N/A' ?></small>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Utvikling</h5>
                    </div>
                    <div class="card-body">
                        <p class="small">
                            <strong>Webutvikler:</strong><br>
                            AI-assistert utvikling med moderne verktøy
                        </p>
                        <p class="small">
                            <strong>AI-utvikling:</strong><br>
                            Dette systemet er utviklet med bruk av kunstig intelligens (AI) for å sikre effektiv og moderne kodekvalitet.
                        </p>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Kontakt</h5>
                    </div>
                    <div class="card-body">
                        <p class="small">
                            <strong>Utviklet av:</strong><br>
                            Jaktfeltcup Team
                        </p>
                        <p class="small">
                            <strong>Support:</strong><br>
                            <a href="mailto:support@jaktfeltcup.no">support@jaktfeltcup.no</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_footer(); ?>
