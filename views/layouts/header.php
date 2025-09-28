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
        body {
            position: relative;
        }
        
        /* Make sections more transparent to show background logo */
        section {
            background-color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .hero-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%) !important;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('<?= \Jaktfeltcup\Helpers\ImageHelper::getLogoUrl() ?>');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.25;
            z-index: -1;
            pointer-events: none;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            position: relative;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 50%;
            right: 50px;
            transform: translateY(-50%);
            width: 300px;
            height: 300px;
            background-image: url('<?= \Jaktfeltcup\Helpers\ImageHelper::getLogoUrl() ?>');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.7;
            z-index: 1;
            pointer-events: none;
        }
        
        .hero-section > * {
            position: relative;
            z-index: 2;
        }
        
        @media (max-width: 768px) {
            .hero-section::before {
                width: 200px;
                height: 200px;
                right: 20px;
                opacity: 0.6;
            }
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
        
        /* Content Editor Styles */
        .content-editor {
            position: relative;
            border: 2px dashed transparent;
            padding: 10px;
            margin: 5px 0;
            transition: all 0.3s ease;
        }
        
        .content-editor:hover {
            border-color: #007bff;
            background-color: rgba(0, 123, 255, 0.05);
        }
        
        .edit-trigger {
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0.3;
            transition: opacity 0.3s ease;
        }
        
        .content-editor:hover .edit-trigger {
            opacity: 1;
        }
        
        .edit-btn {
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            background-color: #fff;
            border: 1px solid #007bff;
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
        
        .edit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.25);
            background-color: #007bff;
            color: white;
        }
        
        /* Modal Styles */
        .content-edit-modal .modal-dialog {
            max-width: 600px;
        }
        
        .content-edit-modal .form-control {
            border-radius: 8px;
        }
        
        .content-edit-modal .btn {
            border-radius: 6px;
        }
    </style>
</head>
<body class="<?= $body_class ?? '' ?>">
    <?php if ($show_navigation): ?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-bullseye me-2"></i>Jaktfeltcup
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'landing' ? 'active' : '' ?>" href="<?= base_url('/') ?>">Hjem</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'arrangor' ? 'active' : '' ?>" href="<?= base_url('arrangor') ?>">Arrangør</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'sponsor' ? 'active' : '' ?>" href="<?= base_url('sponsor') ?>">Sponsor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'deltaker' ? 'active' : '' ?>" href="<?= base_url('deltaker') ?>">Deltaker</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'publikum' ? 'active' : '' ?>" href="<?= base_url('publikum') ?>">Publikum</a>
                    </li>
                    <?php if (in_array($current_page, ['results', 'standings', 'competition-results'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'results' ? 'active' : '' ?>" href="<?= base_url('results') ?>">Resultater</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'standings' ? 'active' : '' ?>" href="<?= base_url('standings') ?>">Sammenlagt</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'about' ? 'active' : '' ?>" href="<?= base_url('about') ?>">Om</a>
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
                    <?php elseif ($current_page === 'deltaker' || strpos($_SERVER['REQUEST_URI'], '/deltaker') !== false): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('login') ?>">Logg inn</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>">Registrer</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
