<?php
/**
 * Login Page
 */

// Start session
session_start();

// Include required files
require_once __DIR__ . '/../../config/config.php';

$page_title = 'Logg inn';
$success_message = $_SESSION['success'] ?? '';
$error_message = $_SESSION['error'] ?? '';

// Clear messages
unset($_SESSION['success'], $_SESSION['error']);
?>

<?php
// Set page variables
$current_page = 'login';
$body_class = 'bg-light';
?>

<?php include_header(); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card mt-5">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                            <h3>Logg inn</h3>
                            <p class="text-muted">Tilgang til Jaktfeltcup</p>
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

                        <form method="POST" action="<?= base_url('login') ?>">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-post</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Passord</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Husk meg
                                </label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Logg inn
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="<?= base_url('forgot-password') ?>" class="text-decoration-none">Glemt passord?</a>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            <p class="mb-0">Har du ikke konto?</p>
                            <a href="<?= base_url('register') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Registrer deg
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_footer(); ?>
