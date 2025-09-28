<?php
// Dokumentasjon side for Jaktfeltcup
$page_title = "Brukermanual - Jaktfeltcup";
$current_page = 'dokumentasjon';
include __DIR__ . '/../layouts/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-3">
            <!-- Sidebar med innholdsfortegnelse -->
            <div class="sticky-top" style="top: 20px;">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Innholdsfortegnelse</h5>
                    </div>
                    <div class="card-body">
                        <nav class="nav flex-column">
                            <a class="nav-link" href="#introduksjon">1. Introduksjon</a>
                            <a class="nav-link" href="#første-gang">2. Første gang</a>
                            <a class="nav-link" href="#brukerroller">3. Brukerroller</a>
                            <a class="nav-link" href="#navigasjon">4. Navigasjon</a>
                            <a class="nav-link" href="#arrangør-funksjoner">5. Arrangør-funksjoner</a>
                            <a class="nav-link" href="#sponsor-funksjoner">6. Sponsor-funksjoner</a>
                            <a class="nav-link" href="#deltaker-funksjoner">7. Deltaker-funksjoner</a>
                            <a class="nav-link" href="#publikum-funksjoner">8. Publikum-funksjoner</a>
                            <a class="nav-link" href="#admin-funksjoner">9. Admin-funksjoner</a>
                            <a class="nav-link" href="#innholdsredigering">10. Innholdsredigering</a>
                            <a class="nav-link" href="#feilsøking">11. Feilsøking</a>
                            <a class="nav-link" href="#kontakt">12. Kontakt</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <!-- Hovedinnhold -->
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0">Jaktfeltcup - Brukerdokumentasjon</h1>
                    <p class="text-muted mb-0">Komplett guide for alle brukere</p>
                </div>
                <div class="card-body">
                    
                    <!-- Introduksjon -->
                    <section id="introduksjon" class="mb-5">
                        <h2>1. Introduksjon</h2>
                        <p>Jaktfeltcup er en webapplikasjon for administrasjon av skytekonkurranser. Systemet støtter flere brukerroller og tilbyr funksjoner for arrangører, sponsorer, deltakere og publikum.</p>
                        
                        <h4>Hovedfunksjoner</h4>
                        <ul>
                            <li><strong>Arrangører</strong>: Opprett og administrer stevner</li>
                            <li><strong>Sponsorer</strong>: Få synlighet og markedsføring</li>
                            <li><strong>Deltakere</strong>: Meld deg på stevner og se resultater</li>
                            <li><strong>Publikum</strong>: Følg med på resultater og nyheter</li>
                            <li><strong>Admin</strong>: Administrer systemet og brukere</li>
                        </ul>
                    </section>

                    <!-- Første gang -->
                    <section id="første-gang" class="mb-5">
                        <h2>2. Første gang</h2>
                        
                        <h4>Tilgang til systemet</h4>
                        <ol>
                            <li>Gå til <code><?= base_url() ?></code></li>
                            <li>Velg din rolle fra hovedmenyen</li>
                            <li>Logg inn eller registrer deg som ny bruker</li>
                        </ol>

                        <h4>Oppsett av første admin</h4>
                        <p>Hvis du er systemadministrator og trenger å sette opp første admin-bruker:</p>
                        <div class="alert alert-info">
                            <h5>Metode 1: PHP script</h5>
                            <code>php scripts/setup/create_single_user.php</code>
                            
                            <h5 class="mt-3">Metode 2: SQL script</h5>
                            <p>Kjør <code>database/test_users.sql</code> i MySQL</p>
                            
                            <h5 class="mt-3">Metode 3: Web-basert</h5>
                            <p>Gå til <a href="<?= base_url('admin/setup-admin') ?>"><?= base_url('admin/setup-admin') ?></a></p>
                        </div>
                    </section>

                    <!-- Brukerroller -->
                    <section id="brukerroller" class="mb-5">
                        <h2>3. Brukerroller</h2>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Admin</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Tilgang:</strong> Full systemtilgang</p>
                                        <p><strong>Funksjoner:</strong></p>
                                        <ul>
                                            <li>Administrer alle brukere og roller</li>
                                            <li>Tilgang til alle admin-funksjoner</li>
                                            <li>Kan redigere alt innhold</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">Database Manager</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Tilgang:</strong> Database-administrasjon</p>
                                        <p><strong>Funksjoner:</strong></p>
                                        <ul>
                                            <li>Administrer database</li>
                                            <li>Importer/eksporter data</li>
                                            <li>Database-vedlikehold</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0">Content Manager</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Tilgang:</strong> Innholdsredigering</p>
                                        <p><strong>Funksjoner:</strong></p>
                                        <ul>
                                            <li>Rediger tekst på alle sider</li>
                                            <li>Administrer nyheter</li>
                                            <li>Administrer sponsorer</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">Role Manager</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Tilgang:</strong> Bruker- og rollestyring</p>
                                        <p><strong>Funksjoner:</strong></p>
                                        <ul>
                                            <li>Administrer brukere</li>
                                            <li>Tildele roller</li>
                                            <li>Brukeradministrasjon</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Navigasjon -->
                    <section id="navigasjon" class="mb-5">
                        <h2>4. Navigasjon</h2>
                        
                        <h4>Hovedmeny</h4>
                        <ul>
                            <li><strong>Hjem</strong>: Landingsside med oversikt</li>
                            <li><strong>Arrangør</strong>: Informasjon for arrangører</li>
                            <li><strong>Sponsor</strong>: Informasjon for sponsorer</li>
                            <li><strong>Deltaker</strong>: Deltaker-funksjoner</li>
                            <li><strong>Publikum</strong>: Offentlig informasjon</li>
                        </ul>

                        <h4>Betinget navigasjon</h4>
                        <ul>
                            <li><strong>Resultater/Sammenlagt</strong>: Kun synlig i resultater-seksjonen</li>
                            <li><strong>Logg inn/Registrer</strong>: Kun synlig i deltaker-seksjonen</li>
                            <li><strong>Admin</strong>: Kun tilgjengelig via adresselinje for admin-brukere</li>
                        </ul>
                    </section>

                    <!-- Arrangør-funksjoner -->
                    <section id="arrangør-funksjoner" class="mb-5">
                        <h2>5. Arrangør-funksjoner</h2>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Hovedside</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('arrangor') ?>"><?= base_url('arrangor') ?></a></p>
                                        <p><strong>Innhold:</strong> Informasjon om å bli arrangør</p>
                                        <p><strong>Redigering:</strong> Content Manager kan redigere tekst</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Bli arrangør</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('arrangor/bli-arrangor') ?>"><?= base_url('arrangor/bli-arrangor') ?></a></p>
                                        <p><strong>Funksjon:</strong> Informasjon om prosessen</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Kontakt</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('arrangor/kontakt') ?>"><?= base_url('arrangor/kontakt') ?></a></p>
                                        <p><strong>Funksjon:</strong> Kontaktinformasjon</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4>Admin-funksjoner</h4>
                        <ul>
                            <li>Opprett stevner</li>
                            <li>Administrer deltakere</li>
                            <li>Legg inn resultater</li>
                            <li>Kommuniser med deltakere</li>
                        </ul>
                    </section>

                    <!-- Sponsor-funksjoner -->
                    <section id="sponsor-funksjoner" class="mb-5">
                        <h2>6. Sponsor-funksjoner</h2>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Hovedside</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('sponsor') ?>"><?= base_url('sponsor') ?></a></p>
                                        <p><strong>Innhold:</strong> Informasjon om sponsormuligheter</p>
                                        <p><strong>Redigering:</strong> Content Manager kan redigere tekst</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Sponsor-pakker</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('sponsor/pakker') ?>"><?= base_url('sponsor/pakker') ?></a></p>
                                        <p><strong>Funksjon:</strong> Oversikt over tilgjengelige pakker</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Sponsor-presentasjon</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('sponsor/presentasjon') ?>"><?= base_url('sponsor/presentasjon') ?></a></p>
                                        <p><strong>Funksjon:</strong> Viser alle aktive sponsorer</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Kontakt</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('sponsor/kontakt') ?>"><?= base_url('sponsor/kontakt') ?></a></p>
                                        <p><strong>Funksjon:</strong> Kontaktinformasjon for sponsorer</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Deltaker-funksjoner -->
                    <section id="deltaker-funksjoner" class="mb-5">
                        <h2>7. Deltaker-funksjoner</h2>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Hovedside</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('deltaker') ?>"><?= base_url('deltaker') ?></a></p>
                                        <p><strong>Innhold:</strong> Informasjon for deltakere</p>
                                        <p><strong>Redigering:</strong> Content Manager kan redigere tekst</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Meld deg på</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('deltaker/meld-deg-pa') ?>"><?= base_url('deltaker/meld-deg-pa') ?></a></p>
                                        <p><strong>Funksjon:</strong> Påmelding til stevner</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Regler</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('deltaker/regler') ?>"><?= base_url('deltaker/regler') ?></a></p>
                                        <p><strong>Funksjon:</strong> Regler og retningslinjer</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4>Dashboard</h4>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Deltaker Dashboard</h5>
                                <p><strong>URL:</strong> <a href="<?= base_url('participant/dashboard') ?>"><?= base_url('participant/dashboard') ?></a></p>
                                <p><strong>Funksjoner:</strong></p>
                                <ul>
                                    <li>Se dine påmeldinger</li>
                                    <li>Se dine resultater</li>
                                    <li>Rediger profil</li>
                                    <li>Hurtighandlinger</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Publikum-funksjoner -->
                    <section id="publikum-funksjoner" class="mb-5">
                        <h2>8. Publikum-funksjoner</h2>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Hovedside</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('publikum') ?>"><?= base_url('publikum') ?></a></p>
                                        <p><strong>Innhold:</strong> Offentlig informasjon</p>
                                        <p><strong>Redigering:</strong> Content Manager kan redigere tekst</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Kalender</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('publikum/kalender') ?>"><?= base_url('publikum/kalender') ?></a></p>
                                        <p><strong>Funksjon:</strong> Stevne-kalender</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Nyheter</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('publikum/nyheter') ?>"><?= base_url('publikum/nyheter') ?></a></p>
                                        <p><strong>Funksjon:</strong> Siste nytt</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4>Resultater</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Resultater</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('results') ?>"><?= base_url('results') ?></a></p>
                                        <p><strong>Funksjon:</strong> Se resultater fra stevner</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Sammenlagt</h5>
                                        <p><strong>URL:</strong> <a href="<?= base_url('standings') ?>"><?= base_url('standings') ?></a></p>
                                        <p><strong>Funksjon:</strong> Sammenlagtstilling</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Admin-funksjoner -->
                    <section id="admin-funksjoner" class="mb-5">
                        <h2>9. Admin-funksjoner</h2>
                        
                        <div class="alert alert-warning">
                            <strong>Merk:</strong> Admin-funksjoner krever spesielle roller og tilgang.
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Admin Dashboard</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>URL:</strong> <a href="<?= base_url('admin') ?>"><?= base_url('admin') ?></a></p>
                                        <p><strong>Tilgang:</strong> Admin-roller</p>
                                        <p><strong>Funksjoner:</strong></p>
                                        <ul>
                                            <li>Database-administrasjon</li>
                                            <li>Innholdsredigering</li>
                                            <li>Bruker- og rollestyring</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">Database-administrasjon</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>URL:</strong> <a href="<?= base_url('admin/database') ?>"><?= base_url('admin/database') ?></a></p>
                                        <p><strong>Tilgang:</strong> Database Manager</p>
                                        <p><strong>Funksjoner:</strong></p>
                                        <ul>
                                            <li>Database-oversikt</li>
                                            <li>Import/eksport</li>
                                            <li>Vedlikehold</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0">Innholdsredigering</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>URL:</strong> <a href="<?= base_url('admin/content') ?>"><?= base_url('admin/content') ?></a></p>
                                        <p><strong>Tilgang:</strong> Content Manager</p>
                                        <p><strong>Funksjoner:</strong></p>
                                        <ul>
                                            <li>Rediger nyheter</li>
                                            <li>Administrer sponsorer</li>
                                            <li>Tekstinnhold</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">Bruker- og rollestyring</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>URL:</strong> <a href="<?= base_url('admin/roles') ?>"><?= base_url('admin/roles') ?></a></p>
                                        <p><strong>Tilgang:</strong> Role Manager</p>
                                        <p><strong>Funksjoner:</strong></p>
                                        <ul>
                                            <li>Administrer brukere</li>
                                            <li>Tildele roller</li>
                                            <li>Brukeradministrasjon</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Innholdsredigering -->
                    <section id="innholdsredigering" class="mb-5">
                        <h2>10. Innholdsredigering</h2>
                        
                        <h4>Inline-redigering</h4>
                        <p>Content Manager kan redigere tekst direkte på sidene:</p>
                        <ol>
                            <li><strong>Logg inn som Content Manager</strong></li>
                            <li><strong>Gå til en side</strong> (f.eks. <a href="<?= base_url('arrangor') ?>"><?= base_url('arrangor') ?></a>)</li>
                            <li><strong>Hover over tekst</strong> → Blå kantlinje vises</li>
                            <li><strong>Klikk "Rediger"</strong> → Popup-modal åpnes</li>
                            <li><strong>Rediger tekst</strong> → Klikk "Lagre"</li>
                            <li><strong>Endringer lagres</strong> automatisk</li>
                        </ol>

                        <h4>Admin-redigering</h4>
                        <ul>
                            <li><strong>Nyheter</strong>: <a href="<?= base_url('admin/content') ?>"><?= base_url('admin/content') ?></a> → Nyheter</li>
                            <li><strong>Sponsorer</strong>: <a href="<?= base_url('admin/content') ?>"><?= base_url('admin/content') ?></a> → Sponsorer</li>
                            <li><strong>Tekstinnhold</strong>: <a href="<?= base_url('admin/content/text') ?>"><?= base_url('admin/content/text') ?></a></li>
                        </ul>

                        <h4>Redigerbare elementer</h4>
                        <ul>
                            <li>Hero-seksjoner (tittel og innhold)</li>
                            <li>Seksjonstitler</li>
                            <li>Beskrivelser</li>
                            <li>Card-innhold</li>
                            <li>Call-to-Action seksjoner</li>
                        </ul>
                    </section>

                    <!-- Feilsøking -->
                    <section id="feilsøking" class="mb-5">
                        <h2>11. Feilsøking</h2>
                        
                        <h4>Innloggingsproblemer</h4>
                        <ol>
                            <li><strong>Sjekk brukernavn/e-post</strong></li>
                            <li><strong>Sjekk passord</strong></li>
                            <li><strong>Sjekk at bruker er aktiv</strong> (<code>is_active = 1</code>)</li>
                            <li><strong>Kjør debug-script</strong>: <code>php test_login_debug.php</code></li>
                        </ol>

                        <h4>Database-problemer</h4>
                        <ol>
                            <li><strong>Sjekk database-tilkobling</strong> i <code>config/config.php</code></li>
                            <li><strong>Kjør database-setup</strong>: <code>database/schema.sql</code></li>
                            <li><strong>Sjekk tabellstruktur</strong></li>
                        </ol>

                        <h4>URL-problemer</h4>
                        <ol>
                            <li><strong>Sjekk <code>.htaccess</code></strong> fil</li>
                            <li><strong>Sjekk <code>base_url</code></strong> i config</li>
                            <li><strong>Sjekk server-konfigurasjon</strong></li>
                        </ol>

                        <h4>Bilde-problemer</h4>
                        <ol>
                            <li><strong>Sjekk filstier</strong> i <code>assets/images/</code></li>
                            <li><strong>Sjekk tillatelser</strong> på filer</li>
                            <li><strong>Sjekk ad-blocker</strong> (kan blokkere bilder)</li>
                        </ol>
                    </section>

                    <!-- Kontakt -->
                    <section id="kontakt" class="mb-5">
                        <h2>12. Kontakt</h2>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Support</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>E-post:</strong> support@jaktfeltcup.no</p>
                                        <p><strong>Telefon:</strong> +47 XXX XX XXX</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Utvikler</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>GitHub:</strong> [Repository URL]</p>
                                        <p><strong>Dokumentasjon:</strong> [Dokumentasjon URL]</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Systemadministrator</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>E-post:</strong> admin@jaktfeltcup.no</p>
                                        <p><strong>Telefon:</strong> +47 XXX XX XXX</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Vedlegg -->
                    <section class="mb-5">
                        <h2>Vedlegg</h2>
                        
                        <h4>A. Bruker-scripts</h4>
                        <div class="alert alert-light">
                            <pre><code># Opprett enkelt bruker
php scripts/setup/create_single_user.php

# Opprett flere brukere
php scripts/setup/create_users_php.php

# Fra CSV-fil
php scripts/setup/create_users_from_csv.php users.csv

# Fra JSON-fil
php scripts/setup/create_users_from_json.php users.json</code></pre>
                        </div>

                        <h4>B. Database-scripts</h4>
                        <div class="alert alert-light">
                            <pre><code>-- Opprett test-brukere
SOURCE database/test_users.sql;

-- Opprett alle brukere
SOURCE database/create_users.sql;</code></pre>
                        </div>

                        <h4>C. Konfigurasjon</h4>
                        <div class="alert alert-light">
                            <pre><code>// config/config.php
$app_config = [
    'base_url' => '/jaktfeltcup/',  // Lokalt
    'base_url' => '/',              // Produksjon
];</code></pre>
                        </div>
                    </section>

                    <hr>
                    <p class="text-muted">
                        <em>Dokumentasjon oppdatert: <?= date('Y-m-d') ?></em><br>
                        <em>Versjon: 1.0</em>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}

/* Sticky sidebar */
.sticky-top {
    position: sticky;
    top: 20px;
}

/* Active navigation link */
.nav-link.active {
    background-color: #007bff;
    color: white;
}

/* Code blocks */
pre code {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 1rem;
    display: block;
}

/* Card hover effects */
.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    transition: box-shadow 0.15s ease-in-out;
}
</style>

<script>
// Smooth scrolling and active navigation
document.addEventListener('DOMContentLoaded', function() {
    // Update active nav link on scroll
    window.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');
        
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (scrollY >= (sectionTop - 200)) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
