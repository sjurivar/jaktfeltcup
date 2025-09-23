<?php

namespace Jaktfeltcup\Middleware;

use Jaktfeltcup\Core\Database;
use Jaktfeltcup\Auth\AuthService;

/**
 * Authentication Middleware
 * 
 * Handles user authentication and session management.
 */
class AuthMiddleware
{
    private AuthService $authService;

    public function __construct()
    {
        // This would be injected in a real implementation
        $this->authService = new AuthService(new Database(new \Jaktfeltcup\Core\Config()));
    }

    /**
     * Check if user is authenticated
     */
    public function __invoke(): bool
    {
        if (!$this->authService->isAuthenticated()) {
            $this->redirectToLogin();
            return false;
        }

        return true;
    }

    /**
     * Redirect to login page
     */
    private function redirectToLogin(): void
    {
        if ($this->isApiRequest()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
        } else {
            header('Location: /login');
        }
        exit;
    }

    /**
     * Check if this is an API request
     */
    private function isApiRequest(): bool
    {
        return strpos($_SERVER['REQUEST_URI'], '/api/') === 0;
    }
}
