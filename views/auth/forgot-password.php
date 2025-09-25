<?php
/**
 * Forgot Password Page
 */

// Start session
session_start();

// Include required files
require_once __DIR__ . '/../../config/config.php';

$page_title = 'Glemt passord';
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';
$errors = $_SESSION['errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];

// Clear messages
unset($_SESSION['success'], $_SESSION['error'], $_SESSION['errors'], $_SESSION['form_data']);
?>

<?php
// Set page variables
$current_page = 'forgot-password';
$body_class = 'bg-light';
?>

<?php include_header(); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card mt-5">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-key fa-3x text-primary mb-3"></i>
                            <h3>Glemt passord</h3>
                            <p class="text-muted">Skriv inn din e-postadresse for å få tilbakestillingslink</p>
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
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= htmlspecialchars($error_message) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?= base_url('forgot-password') ?>">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-postadresse</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($form_data['email'] ?? '') ?>"
                                       required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send tilbakestillingslink
                                </button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <a href="<?= base_url('login') ?>" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>
                                Tilbake til innlogging
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_footer(); ?>
