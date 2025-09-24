<?php
/**
 * Common Header Layout
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default values
$page_title = $page_title ?? 'Jaktfeltcup';
$page_description = $page_description ?? 'Administrasjonssystem for skyteøvelse';
$additional_css = $additional_css ?? '';
$show_navigation = $show_navigation ?? true;
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - Jaktfeltcup</title>
    <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Additional CSS -->
    <?= $additional_css ?>
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .navbar-brand {
            font-weight: bold;
        }
        .nav-link.active {
            font-weight: 500;
        }
    </style>
</head>
<body class="<?= $body_class ?? '' ?>">
    <?php if ($show_navigation): ?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-bullseye me-2"></i>Jaktfeltcup
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'home' ? 'active' : '' ?>" href="<?= base_url() ?>">Hjem</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'results' ? 'active' : '' ?>" href="<?= base_url('results') ?>">Resultater</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'standings' ? 'active' : '' ?>" href="<?= base_url('standings') ?>">Sammenlagt</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'competitions' ? 'active' : '' ?>" href="<?= base_url('competitions') ?>">Stevner</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="navbar-text me-3">Hei, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Bruker') ?>!</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('participant/dashboard') ?>">Min side</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('logout') ?>">Logg ut</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login') ?>">Logg inn</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>">Registrer</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="<?= base_url('admin/database') ?>">
                            <i class="fas fa-cog me-1"></i>Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
