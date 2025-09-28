<?php
/**
 * Jaktfeltcup - Main Entry Point
 * 
 * This is the main entry point for the Jaktfeltcup web application.
 * It handles routing, authentication, and request processing.
 */

// Simple autoloader without Composer
spl_autoload_register(function ($class) {
    $prefix = 'Jaktfeltcup\\';
    $base_dir = __DIR__ . '/src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load configuration
require_once __DIR__ . '/src/Core/App.php';

// Initialize database connection
$database = new Database($config);

// Get base URL for routing
$base_url = $app_config['base_url'];

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove query string
$path = parse_url($request_uri, PHP_URL_PATH);

// Route handling
// Handle specific competition results
if (preg_match('#^' . $base_url . '/results/(\d+)$#', $path, $matches) || preg_match('#^/results/(\d+)$#', $path, $matches)) {
    $competitionId = $matches[1];
    include __DIR__ . '/views/public/competition-results.php';
    exit;
}

switch ($path) {
        case '/':
        case $base_url . '/':
        case $base_url . '/public/':
            include __DIR__ . '/views/public/landing.php';
            break;
    case '/results':
    case $base_url . '/results':
        include __DIR__ . '/views/public/results.php';
        break;
    case '/standings':
    case $base_url . '/standings':
        include __DIR__ . '/views/public/standings.php';
        break;
    case '/competitions':
    case $base_url . '/competitions':
        include __DIR__ . '/views/public/competitions.php';
        break;
    case '/about':
    case $base_url . '/about':
        include __DIR__ . '/views/public/about.php';
        break;
    
    // Arrangør-seksjon
    case '/arrangor':
    case $base_url . '/arrangor':
        include __DIR__ . '/views/public/arrangor/index.php';
        break;
    case '/arrangor/bli-arrangor':
    case $base_url . '/arrangor/bli-arrangor':
        include __DIR__ . '/views/public/arrangor/bli-arrangor.php';
        break;
    case '/arrangor/kontakt':
    case $base_url . '/arrangor/kontakt':
        include __DIR__ . '/views/public/arrangor/kontakt.php';
        break;
    
    // Sponsor-seksjon
    case '/sponsor':
    case $base_url . '/sponsor':
        include __DIR__ . '/views/public/sponsor/index.php';
        break;
    case '/sponsor/pakker':
    case $base_url . '/sponsor/pakker':
        include __DIR__ . '/views/public/sponsor/pakker.php';
        break;
    case '/sponsor/kontakt':
    case $base_url . '/sponsor/kontakt':
        include __DIR__ . '/views/public/sponsor/kontakt.php';
        break;
    
    // Deltaker-seksjon
    case '/deltaker':
    case $base_url . '/deltaker':
        include __DIR__ . '/views/public/deltaker/index.php';
        break;
    case '/deltaker/meld-deg-pa':
    case $base_url . '/deltaker/meld-deg-pa':
        include __DIR__ . '/views/public/deltaker/meld-deg-pa.php';
        break;
    case '/deltaker/regler':
    case $base_url . '/deltaker/regler':
        include __DIR__ . '/views/public/deltaker/regler.php';
        break;
    
    // Publikum-seksjon
    case '/publikum':
    case $base_url . '/publikum':
        include __DIR__ . '/views/public/publikum/index.php';
        break;
    case '/publikum/kalender':
    case $base_url . '/publikum/kalender':
        include __DIR__ . '/views/public/publikum/kalender.php';
        break;
    case '/publikum/nyheter':
    case $base_url . '/publikum/nyheter':
        include __DIR__ . '/views/public/publikum/nyheter.php';
        break;
    case '/login':
    case $base_url . '/login':
        if ($request_method === 'POST') {
            // Handle login
            include __DIR__ . '/handlers/auth/login.php';
        } else {
            include __DIR__ . '/views/auth/login.php';
        }
        break;
    case '/register':
    case $base_url . '/register':
        if ($request_method === 'POST') {
            // Handle registration
            include __DIR__ . '/handlers/auth/register.php';
        } else {
            include __DIR__ . '/views/auth/register.php';
        }
        break;
    case '/participant/dashboard':
    case $base_url . '/participant/dashboard':
        // Check authentication
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
        include __DIR__ . '/views/participant/dashboard.php';
        break;
    case '/participant/profile':
    case $base_url . '/participant/profile':
        // Check authentication
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
        include __DIR__ . '/views/participant/profile.php';
        break;
    case '/verify-email':
    case $base_url . '/verify-email':
        include __DIR__ . '/views/auth/verify-email.php';
        break;
    case '/verify-email-handler':
    case $base_url . '/verify-email-handler':
        include __DIR__ . '/handlers/auth/verify-email-handler.php';
        break;
    case '/resend-verification':
    case $base_url . '/resend-verification':
        include __DIR__ . '/handlers/auth/resend-verification.php';
        break;
    case '/forgot-password':
    case $base_url . '/forgot-password':
        if ($request_method === 'POST') {
            // Handle forgot password
            include __DIR__ . '/handlers/auth/forgot-password.php';
        } else {
            include __DIR__ . '/views/auth/forgot-password.php';
        }
        break;
    case '/logout':
    case $base_url . '/logout':
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: ' . base_url());
        exit;
        break;
    case '/admin/database':
    case $base_url . '/admin/database':
    case '/admin/database/':
    case $base_url . '/admin/database/':
        include __DIR__ . '/admin/database/index.php';
        break;
    default:
        // Check if it's an admin route
        if (preg_match('#^' . $base_url . '/admin/database/(.+)$#', $path, $matches) || 
            preg_match('#^/admin/database/(.+)$#', $path, $matches)) {
            $adminFile = $matches[1];
            $adminPath = __DIR__ . '/admin/database/' . $adminFile;
            if (file_exists($adminPath)) {
                include $adminPath;
                break;
            }
        }
        
        // Check if it's a scripts route
        if (preg_match('#^' . $base_url . '/scripts/(.+)$#', $path, $matches) || 
            preg_match('#^/scripts/(.+)$#', $path, $matches)) {
            $scriptFile = $matches[1];
            $scriptPath = __DIR__ . '/scripts/' . $scriptFile;
            if (file_exists($scriptPath)) {
                include $scriptPath;
                break;
            }
        }
        
        http_response_code(404);
        include __DIR__ . '/views/errors/404.php';
        break;
}
