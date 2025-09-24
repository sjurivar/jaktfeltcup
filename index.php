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

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Remove query string
$path = parse_url($request_uri, PHP_URL_PATH);

// Route handling
// Handle specific competition results
if (preg_match('#^/jaktfeltcup/results/(\d+)$#', $path, $matches) || preg_match('#^/results/(\d+)$#', $path, $matches)) {
    $competitionId = $matches[1];
    include __DIR__ . '/views/public/competition-results.php';
    exit;
}

switch ($path) {
    case '/':
    case '/jaktfeltcup/':
    case '/jaktfeltcup/public/':
        include __DIR__ . '/views/public/home.php';
        break;
    case '/results':
    case '/jaktfeltcup/results':
        include __DIR__ . '/views/public/results.php';
        break;
    case '/standings':
    case '/jaktfeltcup/standings':
        include __DIR__ . '/views/public/standings.php';
        break;
    case '/competitions':
    case '/jaktfeltcup/competitions':
        include __DIR__ . '/views/public/competitions.php';
        break;
    case '/login':
    case '/jaktfeltcup/login':
        if ($request_method === 'POST') {
            // Handle login
            include __DIR__ . '/handlers/auth/login.php';
        } else {
            include __DIR__ . '/views/auth/login.php';
        }
        break;
    case '/register':
    case '/jaktfeltcup/register':
        if ($request_method === 'POST') {
            // Handle registration
            include __DIR__ . '/handlers/auth/register.php';
        } else {
            include __DIR__ . '/views/auth/register.php';
        }
        break;
    case '/participant/dashboard':
    case '/jaktfeltcup/participant/dashboard':
        // Check authentication
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        include __DIR__ . '/views/participant/dashboard.php';
        break;
    case '/participant/profile':
    case '/jaktfeltcup/participant/profile':
        // Check authentication
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        include __DIR__ . '/views/participant/profile.php';
        break;
    case '/verify-email':
    case '/jaktfeltcup/verify-email':
        include __DIR__ . '/views/auth/verify-email.php';
        break;
    case '/verify-email-handler':
    case '/jaktfeltcup/verify-email-handler':
        include __DIR__ . '/handlers/auth/verify-email-handler.php';
        break;
    case '/resend-verification':
    case '/jaktfeltcup/resend-verification':
        include __DIR__ . '/handlers/auth/resend-verification.php';
        break;
    case '/logout':
    case '/jaktfeltcup/logout':
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: /');
        exit;
        break;
    case '/admin/database':
    case '/jaktfeltcup/admin/database':
    case '/admin/database/':
    case '/jaktfeltcup/admin/database/':
        include __DIR__ . '/admin/database/index.php';
        break;
    default:
        // Check if it's an admin route
        if (preg_match('#^/jaktfeltcup/admin/database/(.+)$#', $path, $matches) || 
            preg_match('#^/admin/database/(.+)$#', $path, $matches)) {
            $adminFile = $matches[1];
            $adminPath = __DIR__ . '/admin/database/' . $adminFile;
            if (file_exists($adminPath)) {
                include $adminPath;
                break;
            }
        }
        
        // Check if it's a scripts route
        if (preg_match('#^/jaktfeltcup/scripts/(.+)$#', $path, $matches) || 
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
