### Routes

All application routes are registered in `routes/web.php` and handled by `Jaktfeltcup\Core\Router`.

```1:70:/workspace/routes/web.php
<?php

use Jaktfeltcup\Controllers\PublicController;
use Jaktfeltcup\Controllers\AuthController;
use Jaktfeltcup\Controllers\ParticipantController;
use Jaktfeltcup\Controllers\OrganizerController;
use Jaktfeltcup\Controllers\AdminController;
use Jaktfeltcup\Middleware\AuthMiddleware;
use Jaktfeltcup\Middleware\RoleMiddleware;

// Public routes (no authentication required)
$router->get('/', [PublicController::class, 'home']);
$router->get('/results', [PublicController::class, 'results']);
$router->get('/results/{competitionId}', [PublicController::class, 'competitionResults']);
$router->get('/standings', [PublicController::class, 'standings']);
$router->get('/competitions', [PublicController::class, 'competitions']);

// Authentication routes
$router->post('/auth/register', [AuthController::class, 'register']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->post('/auth/logout', [AuthController::class, 'logout']);
$router->post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
$router->post('/auth/reset-password', [AuthController::class, 'resetPassword']);
$router->get('/auth/verify-email/{token}', [AuthController::class, 'verifyEmail']);

// Participant routes (authenticated participants)
$router->get('/participant/dashboard', [ParticipantController::class, 'dashboard'], [AuthMiddleware::class, RoleMiddleware::class . ':participant']);
$router->get('/participant/profile', [ParticipantController::class, 'profile'], [AuthMiddleware::class, RoleMiddleware::class . ':participant']);
$router->put('/participant/profile', [ParticipantController::class, 'updateProfile'], [AuthMiddleware::class, RoleMiddleware::class . ':participant']);
$router->get('/participant/registrations', [ParticipantController::class, 'registrations'], [AuthMiddleware::class, RoleMiddleware::class . ':participant']);
$router->post('/participant/register/{competitionId}', [ParticipantController::class, 'registerForCompetition'], [AuthMiddleware::class, RoleMiddleware::class . ':participant']);
$router->delete('/participant/register/{competitionId}', [ParticipantController::class, 'unregisterFromCompetition'], [AuthMiddleware::class, RoleMiddleware::class . ':participant']);
$router->get('/participant/results', [ParticipantController::class, 'myResults'], [AuthMiddleware::class, RoleMiddleware::class . ':participant']);

// Organizer routes (authenticated organizers)
$router->get('/organizer/dashboard', [OrganizerController::class, 'dashboard'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->get('/organizer/competitions', [OrganizerController::class, 'competitions'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->post('/organizer/competitions', [OrganizerController::class, 'createCompetition'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->get('/organizer/competitions/{competitionId}', [OrganizerController::class, 'competitionDetails'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->put('/organizer/competitions/{competitionId}', [OrganizerController::class, 'updateCompetition'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->get('/organizer/competitions/{competitionId}/participants', [OrganizerController::class, 'competitionParticipants'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->post('/organizer/competitions/{competitionId}/participants', [OrganizerController::class, 'addParticipant'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->get('/organizer/competitions/{competitionId}/results', [OrganizerController::class, 'competitionResults'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->post('/organizer/competitions/{competitionId}/results', [OrganizerController::class, 'addResult'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->put('/organizer/competitions/{competitionId}/results/{resultId}', [OrganizerController::class, 'updateResult'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->delete('/organizer/competitions/{competitionId}/results/{resultId}', [OrganizerController::class, 'deleteResult'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);
$router->post('/organizer/competitions/{competitionId}/lock', [OrganizerController::class, 'lockCompetition'], [AuthMiddleware::class, RoleMiddleware::class . ':organizer']);

// Admin routes (authenticated admins)
$router->get('/admin/dashboard', [AdminController::class, 'dashboard'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->get('/admin/users', [AdminController::class, 'users'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->post('/admin/users', [AdminController::class, 'createUser'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->put('/admin/users/{userId}', [AdminController::class, 'updateUser'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->delete('/admin/users/{userId}', [AdminController::class, 'deleteUser'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->get('/admin/seasons', [AdminController::class, 'seasons'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->post('/admin/seasons', [AdminController::class, 'createSeason'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->put('/admin/seasons/{seasonId}', [AdminController::class, 'updateSeason'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->get('/admin/categories', [AdminController::class, 'categories'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->post('/admin/categories', [AdminController::class, 'createCategory'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->put('/admin/categories/{categoryId}', [AdminController::class, 'updateCategory'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->get('/admin/point-systems', [AdminController::class, 'pointSystems'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->post('/admin/point-systems', [AdminController::class, 'createPointSystem'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->put('/admin/point-systems/{systemId}', [AdminController::class, 'updatePointSystem'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->get('/admin/notifications', [AdminController::class, 'notifications'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);
$router->post('/admin/notifications', [AdminController::class, 'sendNotification'], [AuthMiddleware::class, RoleMiddleware::class . ':admin']);

// API routes for mobile/offline functionality
$router->get('/api/offline/sync', [PublicController::class, 'offlineSync'], [AuthMiddleware::class]);
$router->post('/api/offline/sync', [PublicController::class, 'offlineSyncData'], [AuthMiddleware::class]);
```

Notes:
- `RoleMiddleware` is referenced but not present in `src/Middleware/` in this repository snapshot. If implemented, it likely enforces role-based access like `':participant'`.
- Many organizer/admin controller actions are referenced but their classes are not part of this snapshot.

#### Example requests

- Get competitions
```bash
curl -X GET http://localhost:8000/competitions
```

- Get specific competition results
```bash
curl -X GET http://localhost:8000/results/42
```

- Offline sync (GET since timestamp)
```bash
curl -G http://localhost:8000/api/offline/sync --data-urlencode "last_sync=2025-01-01T00:00:00Z"
```

- Offline sync (POST data)
```bash
curl -X POST http://localhost:8000/api/offline/sync \
  -H 'Content-Type: application/json' \
  -d '[{"type":"result","payload":{}}]'
```

