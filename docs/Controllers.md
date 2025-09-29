### Controllers

This document covers public controller methods and how to use them via routes.

---

### PublicController (`Jaktfeltcup\Controllers\PublicController`)

Constructor:
- `__construct(Database $database, Config $config)`

Public methods:
- `home(): void` — Render landing page `views/public/home.php`.
- `results(): void` — List competitions with published results for the active season.
- `competitionResults(array $params): void` — Show results for a specific competition (`{competitionId}`).
- `standings(): void` — Show season standings.
- `competitions(): void` — List upcoming/published competitions with registration counts.
- `offlineSync(): void` — JSON endpoint for fetching pending offline sync items.
- `offlineSyncData(): void` — JSON endpoint to submit offline sync data (POST JSON array).

Examples:
```bash
# Home
curl -i http://localhost:8000/

# All results for active season
curl -i http://localhost:8000/results

# Specific competition
curl -i http://localhost:8000/results/42

# Standings
curl -i http://localhost:8000/standings

# Upcoming competitions
curl -i http://localhost:8000/competitions

# Offline sync (GET)
curl -G http://localhost:8000/api/offline/sync --data-urlencode "last_sync=2025-01-01T00:00:00Z"

# Offline sync (POST JSON)
curl -X POST http://localhost:8000/api/offline/sync \
  -H 'Content-Type: application/json' \
  -d '[{"type":"result","payload":{}}]'
```

---

### ParticipantController (`Jaktfeltcup\Controllers\ParticipantController`)

Constructor:
- `__construct(Database $database, AuthService $authService)`

Public methods:
- `dashboard(): void` — Participant’s overview (registrations and results).
- `profile(): void` — Show profile page.
- `updateProfile(): void` — Handle profile updates (expects POST; redirects on success/error).
- `registrations(): void` — List user’s registrations.
- `registerForCompetition(array $params): void` — GET to render form, POST to create registration; requires `{competitionId}`.
- `unregisterFromCompetition(array $params): void` — Delete a registration for `{competitionId}` if allowed.
- `myResults(): void` — Show the user’s results.

Route usage examples:
```bash
# Get registrations (requires auth via middleware)
curl -i http://localhost:8000/participant/registrations

# Register for a competition (POST form)
curl -X POST http://localhost:8000/participant/register/42 \
  -d 'category_id=3' -d 'notes=Ser frem til!' 

# Unregister from a competition
curl -X DELETE http://localhost:8000/participant/register/42
```

Notes:
- Redirects are used for unauthenticated access; API-style JSON is not returned by these endpoints.
- Server-side sessions are used by `AuthService` to determine the current user.

---

### Auth-related controller endpoints

`routes/web.php` references an `AuthController`, but it is not included in this repository snapshot. Expected actions based on routes:
- `POST /auth/register` → Create user account
- `POST /auth/login` → Authenticate and start session
- `POST /auth/logout` → Destroy session
- `POST /auth/forgot-password` → Initiate password reset
- `POST /auth/reset-password` → Complete password reset
- `GET /auth/verify-email/{token}` → Verify user email

These would typically delegate to `Jaktfeltcup\Auth\AuthService` and `Jaktfeltcup\Services\EmailService`.

---

### Organizer/Admin controllers

`routes/web.php` references `OrganizerController` and `AdminController`, but their implementations are not present here. Based on routes, they are expected to provide CRUD for competitions, results, users, seasons, categories, point-systems, and notification dispatch.

