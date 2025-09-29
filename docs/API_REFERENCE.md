### API Reference

This section documents all public APIs, classes, functions, and components in the project, with examples and usage instructions.

#### Contents
- [Routes](./ROUTES.md)
- [Core](./Core.md)
- [Controllers](./Controllers.md)
- [Services](./Services.md)
- [Helpers](./Helpers.md)
- [Data Service](./Data.md)
- [Middleware](./Middleware.md)

#### Conventions
- **Namespaces**: PHP namespaces follow `Jaktfeltcup\<Area>`.
- **HTTP JSON**: Endpoints returning JSON set `Content-Type: application/json`.
- **Views**: Controllers render PHP views under `views/` using simple includes.

#### Quickstart
```php
// Minimal bootstrapping example
$config = new Jaktfeltcup\Core\Config();
$db     = new Jaktfeltcup\Core\Database($config->get('database'));
$router = new Jaktfeltcup\Core\Router();

$public = new Jaktfeltcup\Controllers\PublicController($db, $config);
$router->get('/', [$public, 'home']);

// Dispatch the incoming request
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
```

See each section for full details and more examples.

