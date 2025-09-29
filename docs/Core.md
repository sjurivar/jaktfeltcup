### Core

Core components provide routing, configuration, DB access, and application composition.

---

### Router (`Jaktfeltcup\Core\Router`)

Public API:
- `addRoute(string $method, string $path, callable $handler, array $middleware = []): void`
- `get(string $path, callable $handler, array $middleware = []): void`
- `post(string $path, callable $handler, array $middleware = []): void`
- `put(string $path, callable $handler, array $middleware = []): void`
- `delete(string $path, callable $handler, array $middleware = []): void`
- `dispatch(string $method, string $uri): void`

Path params use `{param}` placeholders and are made available to the handler as an associative array.

Example:
```php
$router = new Jaktfeltcup\Core\Router();
$router->get('/hello/{name}', function(array $params) {
    echo 'Hello ' . htmlspecialchars($params['name']);
});

// Will output: Hello Ada
$router->dispatch('GET', '/hello/Ada');
```

---

### Config (`Jaktfeltcup\Core\Config`)

Loads configuration from environment variables into namespaced keys.

Public API:
- `__construct()`
- `get(string $key, mixed $default = null): mixed`
- `has(string $key): bool`
- `all(): array`

Example:
```php
$config = new Jaktfeltcup\Core\Config();
$dbHost = $config->get('database.host');
$mailFrom = $config->get('mail.from_address', 'noreply@example.com');
```

---

### Database (`Jaktfeltcup\Core\Database`)

Thin PDO wrapper configured from `Config::get('database')`.

Public API:
- `__construct(array $config)`
- `query(string $sql, array $params = []) : array` (alias of `queryAll`)
- `queryAll(string $sql, array $params = []) : array`
- `queryOne(string $sql, array $params = []) : ?array`
- `execute(string $sql, array $params = []) : int` (affected rows)
- `lastInsertId() : string`

Example:
```php
$db = new Jaktfeltcup\Core\Database($config->get('database'));

// Fetch many
$rows = $db->queryAll('SELECT * FROM competitions WHERE is_published = 1');

// Fetch one
$row = $db->queryOne('SELECT * FROM competitions WHERE id = ?', [42]);

// Execute (insert/update/delete)
$affected = $db->execute('UPDATE competitions SET is_published = 1 WHERE id = ?', [42]);
```

---

### Application (`Jaktfeltcup\Core\Application`)

Composes core services and exposes accessors.

Public API:
- `__construct(Config $config, Database $database)`
- `getService(string $name): mixed` (e.g., `auth`, `mail`, `sms`, `notification`)
- `getConfig(): Config`
- `getDatabase(): Database`

Example:
```php
$app = new Jaktfeltcup\Core\Application($config, $db);
$mail = $app->getService('mail');
$mail->send('user@example.com', 'Subject', 'Body');
```

