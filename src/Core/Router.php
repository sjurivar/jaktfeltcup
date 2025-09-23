<?php

namespace Jaktfeltcup\Core;

/**
 * Simple Router Implementation
 * 
 * Handles HTTP routing and request dispatching.
 */
class Router
{
    private array $routes = [];
    private array $middleware = [];

    /**
     * Add a route
     */
    public function addRoute(string $method, string $path, callable $handler, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    /**
     * Add GET route
     */
    public function get(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    /**
     * Add POST route
     */
    public function post(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    /**
     * Add PUT route
     */
    public function put(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    /**
     * Add DELETE route
     */
    public function delete(string $path, callable $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    /**
     * Dispatch request to appropriate handler
     */
    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri)) {
                $this->executeRoute($route, $uri);
                return;
            }
        }

        // No route found
        $this->handleNotFound();
    }

    /**
     * Check if route path matches URI
     */
    private function matchPath(string $routePath, string $uri): bool
    {
        // Convert route path to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        return preg_match($pattern, $uri);
    }

    /**
     * Execute route handler
     */
    private function executeRoute(array $route, string $uri): void
    {
        // Extract route parameters
        $params = $this->extractParams($route['path'], $uri);

        // Execute middleware
        foreach ($route['middleware'] as $middleware) {
            if (is_callable($middleware)) {
                $result = $middleware();
                if ($result === false) {
                    return; // Middleware blocked the request
                }
            }
        }

        // Execute handler
        $handler = $route['handler'];
        if (is_callable($handler)) {
            $handler($params);
        }
    }

    /**
     * Extract parameters from URI
     */
    private function extractParams(string $routePath, string $uri): array
    {
        $params = [];
        $routeParts = explode('/', trim($routePath, '/'));
        $uriParts = explode('/', trim($uri, '/'));

        foreach ($routeParts as $index => $part) {
            if (preg_match('/\{([^}]+)\}/', $part, $matches)) {
                $paramName = $matches[1];
                $params[$paramName] = $uriParts[$index] ?? null;
            }
        }

        return $params;
    }

    /**
     * Handle 404 Not Found
     */
    private function handleNotFound(): void
    {
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
}
