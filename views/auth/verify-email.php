<?php
/**
 * Email Verification Page
 */

// Start session
session_start();

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Services/EmailService.php';

$page_title = 'Bekreft e-postadresse';
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';
$warning_message = $_SESSION['warning'] ?? '';

// Clear messages
unset($_SESSION['success'], $_SESSION['error'], $_SESSION['warning']);

// Set layout variables
$current_page = 'verify-email';
$body_class = '';
$show_navigation = false;
$show_footer = false;
$additional_css = '
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
    }
    .verification-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 2rem;
        max-width: 500px;
        width: 100%;
    }
    .verification-icon {
        font-size: 4rem;
        color: #28a745;
        margin-bottom: 1rem;
    }
    .code-input {
        font-size: 1.5rem;
        text-align: center;
        letter-spacing: 0.5rem;
        text-transform: uppercase;
    }
    .resend-btn {
        background: none;
        border: none;
        color: #007bff;
        text-decoration: underline;
        cursor: pointer;
    }
    .resend-btn:hover {
        color: #0056b3;
    }
</style>';
$additional_js = '
<script>
    // Auto-format verification code input
    document.getElementById("verification_code").addEventListener("input", function(e) {
        e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, "");
    });

    // Resend verification code
    function resendCode() {
        if (confirm("Vil du sende bekreftelseskoden på nytt?")) {
            fetch("' . base_url('resend-verification') . '", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Bekreftelseskode sendt på nytt!");
                } else {
                    alert("Kunne ikke sende kode på nytt. Prøv igjen senere.");
                }
            })
            .catch(error => {
                alert("En feil oppstod. Prøv igjen senere.");
            });
        }
    }
</script>';
?>

<?php include_header(); ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="verification-container">
                    <div class="text-center">
                        <i class="fas fa-envelope-open-text verification-icon"></i>
                        <h2 class="mb-3">Bekreft din e-postadresse</h2>
                        <p class="text-muted mb-4">
                            Vi har sendt en bekreftelseskode til din e-postadresse. 
                            Skriv inn koden nedenfor for å aktivere kontoen din.
                        </p>
                    </div>

                    <?php if ($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= htmlspecialchars($success_message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= htmlspecialchars($error_message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($warning_message): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= htmlspecialchars($warning_message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= base_url('verify-email-handler') ?>">
                        <div class="mb-4">
                            <label for="verification_code" class="form-label">
                                <i class="fas fa-key me-2"></i>Bekreftelseskode
                            </label>
                            <input 
                                type="text" 
                                class="form-control code-input" 
                                id="verification_code" 
                                name="verification_code" 
                                placeholder="ABCD1234" 
                                maxlength="8"
                                required
                                autocomplete="off"
                            >
                            <div class="form-text">
                                Skriv inn 8-tegns koden du mottok på e-post
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check me-2"></i>Bekreft e-postadresse
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted">
                            Ikke mottatt e-post? 
                            <button type="button" class="resend-btn" onclick="resendCode()">
                                Send på nytt
                            </button>
                        </p>
                        <p class="text-muted">
                            <a href="<?= base_url('login') ?>" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Tilbake til innlogging
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_footer(); ?>