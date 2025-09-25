<?php
/**
 * Test Email Sending
 * Test script for email functionality
 */

// Load configuration
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Helpers/ViewHelper.php';
require_once __DIR__ . '/../../src/Services/EmailService.php';

$page_title = 'Test E-post';
$current_page = 'test_email';
?>

<?php include_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1><i class="fas fa-envelope me-2"></i>Test E-post Utsending</h1>
            <p class="lead">Test e-post funksjonalitet for Jaktfeltcup</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-cog me-2"></i>E-post Test</h5>
                </div>
                <div class="card-body">
                    <?php
                    // Simple test to see if page loads
                    echo "<div class='alert alert-light'>";
                    echo "<h6>🔍 Page Load Test:</h6>";
                    echo "<p><strong>Current Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
                    echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
                    echo "<p><strong>Server:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
                    echo "</div>";
                    
                    // Handle simple GET test
                    if (isset($_GET['simple_test'])) {
                        echo "<div class='alert alert-success'>";
                        echo "<h6>✅ GET Test fungerer!</h6>";
                        echo "<p>Scriptet kjører og kan håndtere GET requests.</p>";
                        echo "<p><strong>GET Parameter:</strong> " . htmlspecialchars($_GET['simple_test']) . "</p>";
                        echo "</div>";
                    }
                    
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_email'])) {
                        $testEmail = 'sjur.ivar@hjellum.net';
                        $testName = 'Sjur Ivar';
                        
                        echo "<div class='alert alert-info'>";
                        echo "<h6>🔍 Debug Information:</h6>";
                        echo "<p><strong>POST Data:</strong> " . print_r($_POST, true) . "</p>";
                        echo "<p><strong>Request Method:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
                        echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
                        echo "</div>";
                        
                        try {
                            echo "<div class='alert alert-success'>";
                            echo "<h6>✅ Script kjører!</h6>";
                            echo "<p>POST request mottatt og behandles...</p>";
                            echo "</div>";
                            
                            // Initialize database and email service
                            $database = new \Jaktfeltcup\Core\Database($db_config);
                            $emailService = new \Jaktfeltcup\Services\EmailService($app_config, $database);
                            
                            echo "<div class='alert alert-success'>";
                            echo "<h6>✅ Services initialisert!</h6>";
                            echo "<p>Database og EmailService er klar.</p>";
                            echo "</div>";
                            
                            echo "<div class='alert alert-info'>";
                            echo "<h6>🔄 Sender test e-post...</h6>";
                            echo "<p><strong>Til:</strong> $testEmail</p>";
                            echo "<p><strong>Navn:</strong> $testName</p>";
                            echo "<p><strong>PHP Mail:</strong> " . (function_exists('mail') ? 'Tilgjengelig' : 'Ikke tilgjengelig') . "</p>";
                            echo "<p><strong>Error Log:</strong> " . ini_get('error_log') . "</p>";
                            echo "<p><strong>Log Errors:</strong> " . (ini_get('log_errors') ? 'Aktivert' : 'Deaktivert') . "</p>";
                            echo "</div>";
                            
                            echo "<div class='alert alert-warning'>";
                            echo "<h6>🔍 Debug: Før Test 1</h6>";
                            echo "<p>Starter Test 1: E-post verifisering...</p>";
                            echo "</div>";
                            
                            // Test 1: Verification email
                            echo "<h6>Test 1: E-post verifisering</h6>";
                            echo "<div class='alert alert-info'>Kaller sendVerificationCode...</div>";
                            
                            $verificationSent = $emailService->sendVerificationCode(999, $testEmail, $testName);
                            
                            echo "<div class='alert alert-info'>sendVerificationCode returnerte: " . ($verificationSent ? 'true' : 'false') . "</div>";
                            
                            if ($verificationSent) {
                                echo "<div class='alert alert-success'>✅ Verifisering e-post sendt!</div>";
                                echo "<p><small>E-posten ble sendt til $testEmail</small></p>";
                            } else {
                                echo "<div class='alert alert-danger'>❌ Kunne ikke sende verifisering e-post</div>";
                                echo "<p><small>Sjekk error_log for detaljer</small></p>";
                            }
                            
                            // Test 2: Password reset email
                            echo "<h6>Test 2: Passord tilbakestilling</h6>";
                            echo "<div class='alert alert-info'>Genererer reset token...</div>";
                            
                            $resetToken = bin2hex(random_bytes(32));
                            echo "<div class='alert alert-info'>Token generert: " . substr($resetToken, 0, 16) . "...</div>";
                            
                            echo "<div class='alert alert-info'>Kaller sendPasswordResetEmail...</div>";
                            $resetSent = $emailService->sendPasswordResetEmail(999, $testEmail, $testName, $resetToken);
                            
                            echo "<div class='alert alert-info'>sendPasswordResetEmail returnerte: " . ($resetSent ? 'true' : 'false') . "</div>";
                            
                            if ($resetSent) {
                                echo "<div class='alert alert-success'>✅ Passord tilbakestilling e-post sendt!</div>";
                                echo "<p><small>E-posten ble sendt til $testEmail</small></p>";
                                echo "<p><small>Reset token: " . substr($resetToken, 0, 16) . "...</small></p>";
                            } else {
                                echo "<div class='alert alert-danger'>❌ Kunne ikke sende passord tilbakestilling e-post</div>";
                                echo "<p><small>Sjekk error_log for detaljer</small></p>";
                            }
                            
                            // Test 3: Custom test email
                            echo "<h6>Test 3: Tilpasset test e-post</h6>";
                            echo "<div class='alert alert-info'>Forbereder tilpasset e-post...</div>";
                            
                            $customSubject = 'Test E-post fra Jaktfeltcup';
                            $customMessage = "
                            <h2>Hei $testName!</h2>
                            <p>Dette er en test e-post fra Jaktfeltcup systemet.</p>
                            <p><strong>Test detaljer:</strong></p>
                            <ul>
                                <li>Dato: " . date('Y-m-d H:i:s') . "</li>
                                <li>Server: " . $_SERVER['HTTP_HOST'] . "</li>
                                <li>PHP versjon: " . PHP_VERSION . "</li>
                                <li>E-post konfigurasjon: " . ($mail_config['host'] ? 'Konfigurert' : 'Ikke konfigurert') . "</li>
                            </ul>
                            <p>Hvis du mottar denne e-posten, fungerer e-post systemet!</p>
                            <hr>
                            <p><small>Denne e-posten ble sendt fra Jaktfeltcup admin panel.</small></p>
                            ";
                            
                            echo "<div class='alert alert-info'>E-post innhold forberedt. Kaller sendEmail...</div>";
                            
                            // Send custom test email
                            $customSent = $emailService->sendEmail($testEmail, $customSubject, $customMessage);
                            
                            echo "<div class='alert alert-info'>sendEmail returnerte: " . ($customSent ? 'true' : 'false') . "</div>";
                            if ($customSent) {
                                echo "<div class='alert alert-success'>✅ Tilpasset test e-post sendt!</div>";
                                echo "<p><small>E-posten ble sendt til $testEmail</small></p>";
                            } else {
                                echo "<div class='alert alert-danger'>❌ Kunne ikke sende tilpasset test e-post</div>";
                                echo "<p><small>Sjekk error_log for detaljer</small></p>";
                            }
                            
                            echo "<div class='alert alert-info mt-3'>";
                            echo "<h6>📋 Test Resultat:</h6>";
                            echo "<p>Alle e-post tester er kjørt. Sjekk error_log for detaljer.</p>";
                            echo "<p><strong>Merk:</strong> I produksjon vil e-postene faktisk bli sendt til $testEmail</p>";
                            echo "</div>";
                            
                        } catch (Exception $e) {
                            echo "<div class='alert alert-danger'>";
                            echo "<h6>❌ Feil under e-post test:</h6>";
                            echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                            echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
                            echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
                            echo "<p><strong>Trace:</strong></p>";
                            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-info'>";
                        echo "<h6>ℹ️ Ingen POST request</h6>";
                        echo "<p>Trykk på 'Send Test E-post' knappen for å teste e-post funksjonaliteten.</p>";
                        echo "</div>";
                    }
                    ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Test E-post Adresse:</label>
                            <input type="email" class="form-control" value="sjur.ivar@hjellum.net" readonly>
                            <div class="form-text">Hardkodet for testing</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Test Navn:</label>
                            <input type="text" class="form-control" value="Sjur Ivar" readonly>
                        </div>
                        
                        <button type="submit" name="test_email" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>
                            Send Test E-post
                        </button>
                    </form>
                    
                    <hr>
                    
                    <a href="?simple_test=1" class="btn btn-outline-secondary">
                        <i class="fas fa-check me-2"></i>Enkel Test (GET)
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2"></i>E-post Konfigurasjon</h5>
                </div>
                <div class="card-body">
                    <h6>SMTP Innstillinger:</h6>
                    <ul class="list-unstyled">
                        <li><strong>Host:</strong> <?= $mail_config['host'] ?: 'Ikke satt' ?></li>
                        <li><strong>Port:</strong> <?= $mail_config['port'] ?: 'Ikke satt' ?></li>
                        <li><strong>Username:</strong> <?= $mail_config['username'] ? 'Satt' : 'Ikke satt' ?></li>
                        <li><strong>Password:</strong> <?= $mail_config['password'] ? 'Satt' : 'Ikke satt' ?></li>
                        <li><strong>Encryption:</strong> <?= $mail_config['encryption'] ?: 'Ikke satt' ?></li>
                    </ul>
                    
                    <h6>Fra Adresse:</h6>
                    <ul class="list-unstyled">
                        <li><strong>E-post:</strong> <?= $mail_config['from_address'] ?: 'Ikke satt' ?></li>
                        <li><strong>Navn:</strong> <?= $mail_config['from_name'] ?: 'Ikke satt' ?></li>
                    </ul>
                    
                    <div class="alert alert-warning mt-3">
                        <small>
                            <strong>Merk:</strong> I test-miljø logges e-postene til error_log. 
                            I produksjon vil de faktisk bli sendt.
                        </small>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-list me-2"></i>E-post Typer</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check-circle text-success me-2"></i>E-post verifisering</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i>Passord tilbakestilling</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i>Tilpasset test e-post</li>
                        <li><i class="fas fa-clock text-warning me-2"></i>Stevne notifikasjoner</li>
                        <li><i class="fas fa-clock text-warning me-2"></i>Resultat notifikasjoner</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_footer(); ?>
