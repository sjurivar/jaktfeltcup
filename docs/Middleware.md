### Middleware

Middleware are callables that may allow or block a request before the route handler runs.

---

### AuthMiddleware (`Jaktfeltcup\Middleware\AuthMiddleware`)

Ensures the user is authenticated via `AuthService`.

Public API:
- `__construct()` — Internally creates `AuthService` with default `Config` and `Database`.
- `__invoke(): bool` — Returns `true` to proceed; otherwise redirects to `/login` or returns `401` JSON for `/api/*`.

Usage (from routes):
```php
$router->get('/participant/dashboard', [ParticipantController::class, 'dashboard'], [AuthMiddleware::class]);
```

Behavior:
- For browser routes, unauthenticated users are redirected to `/login`.
- For API paths starting with `/api/`, a `401 {"error":"Unauthorized"}` JSON is returned.

---

### RoleMiddleware (referenced)

`routes/web.php` references `RoleMiddleware` with syntax like `RoleMiddleware::class . ':participant'`. Its implementation is not present in this repository snapshot. A conventional implementation would:
- Parse the role from the middleware string (e.g., `participant`, `organizer`, `admin`).
- Check the authenticated user’s role using `AuthService::hasRole($role)`.
- On failure, respond with `403` for API routes or redirect to an access-denied page.

