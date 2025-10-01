<?php
/**
 * Common Header Layout
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine current page if not set
if (!isset($current_page)) {
    $request_uri = $_SERVER['REQUEST_URI'];
    if (strpos($request_uri, '/deltaker') !== false) {
        $current_page = 'deltaker';
    } elseif (strpos($request_uri, '/arrangor') !== false) {
        $current_page = 'arrangor';
    } elseif (strpos($request_uri, '/sponsor') !== false) {
        $current_page = 'sponsor';
    } elseif (strpos($request_uri, '/publikum') !== false) {
        $current_page = 'publikum';
    } elseif (strpos($request_uri, '/om-oss') !== false) {
        $current_page = 'om-oss';
    } elseif (strpos($request_uri, '/dokumentasjon') !== false) {
        $current_page = 'dokumentasjon';
    } elseif (strpos($request_uri, '/about') !== false) {
        $current_page = 'about';
    } else {
        $current_page = 'landing';
    }
}

// Set default values
$page_title = $page_title ?? 'Nasjonal 15m Jaktfeltcup';
$page_description = $page_description ?? 'Administrasjonssystem for skyteøvelse';
$additional_css = $additional_css ?? '';
$show_navigation = $show_navigation ?? true;
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - Nasjonal 15m Jaktfeltcup</title>
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
        
        /* Standardized Hero Section - Three-part background */
        .hero-section {
            background: 
                linear-gradient(90deg, rgb(120, 80, 50) 0%, rgb(120, 80, 50) 10%),
                linear-gradient(90deg, rgb(120, 80, 50) 10%, rgb(180, 140, 100) 66%),
                linear-gradient(90deg, rgb(180, 140, 100) 66%, rgb(255, 255, 255) 100%);
            background-size: 10% 100%, 56% 100%, 34% 100%;
            background-position: 0% 0%, 10% 0%, 66% 0%;
            background-repeat: no-repeat;
            color: white;
            padding: 0;
            position: relative;
            height: 300px;
            display: flex;
            align-items: center;
        }
        
        /* Text styling for hero section */
        .hero-section h1,
        .hero-section p {
            color: black;
        }
        
        /* Button styling for hero section */
        .hero-section .btn {
            background-color: rgba(120, 80, 50, 0.9);
            border: 2px solid rgb(120, 80, 50);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .hero-section .btn:hover {
            background-color: rgb(120, 80, 50);
            border-color: rgb(100, 60, 30);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .hero-section .btn-outline-light {
            background-color: transparent;
            border: 2px solid rgb(120, 80, 50);
            color: rgb(120, 80, 50);
        }
        
        .hero-section .btn-outline-light:hover {
            background-color: rgb(120, 80, 50);
            border-color: rgb(100, 60, 30);
            color: white;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            width: 300px;
            height: 300px;
            background-image: url('<?= \Jaktfeltcup\Helpers\ImageHelper::getLogoUrl() ?>');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 1;
            z-index: 1;
            pointer-events: none;
        }
        
        .hero-section > * {
            position: relative;
            z-index: 2;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                height: 200px;
            }
            
            .hero-section::before {
                width: 150px;
                height: 150px;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                opacity: 0.8;
            }
            
            /* Ensure mobile navigation works properly */
            .navbar-collapse {
                background-color: rgba(33, 37, 41, 0.95);
                border-radius: 0.375rem;
                margin-top: 0.5rem;
                padding: 1rem;
            }
            
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .navbar-nav .nav-link:last-child {
                border-bottom: none;
            }
            
            /* Improve mobile button spacing */
            .hero-section .d-flex {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .hero-section .btn {
                width: 100%;
                max-width: 280px;
            }
        }
        
        @media (max-width: 480px) {
            .hero-section {
                height: 180px;
                padding: 1rem 0;
            }
            
            .hero-section::before {
                width: 120px;
                height: 120px;
                right: 5px;
            }
            
            .hero-section h1 {
                font-size: 1.75rem;
            }
            
            .hero-section p {
                font-size: 1rem;
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
        
        /* Mobile navigation improvements */
        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Ensure proper touch targets on mobile */
        @media (max-width: 768px) {
            .nav-link {
                min-height: 44px;
                display: flex;
                align-items: center;
            }
            
            .navbar-nav .nav-item {
                margin: 0;
            }
            
            /* Improve mobile card layouts */
            .card {
                margin-bottom: 1rem;
            }
            
            /* Better mobile button spacing */
            .btn-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
            
            /* Improve mobile text readability */
            .lead {
                font-size: 1.1rem;
            }
            
            /* Better mobile spacing */
            .py-5 {
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }
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
                <i class="fas fa-bullseye me-2"></i>Nasjonal 15m Jaktfeltcup
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
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'om-oss' ? 'active' : '' ?>" href="<?= base_url('om-oss') ?>">Om</a>
                    </li>
                    <?php if (in_array($current_page, ['results', 'standings', 'competition-results'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'results' ? 'active' : '' ?>" href="<?= base_url('results') ?>">Resultater</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'standings' ? 'active' : '' ?>" href="<?= base_url('standings') ?>">Sammenlagt</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php 
                    // Debug: Check session status (commented out for production)
                    $debug_info = '';
                    if (isset($_SESSION['user_id'])) {
                        $debug_info = 'User logged in (ID: ' . $_SESSION['user_id'] . ')';
                    } else {
                        $debug_info = 'User NOT logged in';
                    }
                    // Uncomment next line for debugging:
                    // echo '<!-- Debug: ' . $debug_info . ' -->';
                    ?>
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
                        <?php if ($current_page === 'deltaker' || strpos($_SERVER['REQUEST_URI'], '/deltaker') !== false): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>">Registrer</a>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
