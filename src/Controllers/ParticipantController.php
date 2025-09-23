<?php

namespace Jaktfeltcup\Controllers;

use Jaktfeltcup\Core\Database;
use Jaktfeltcup\Auth\AuthService;

/**
 * Participant Controller
 * 
 * Handles participant-specific functionality like registration, profile management, etc.
 */
class ParticipantController
{
    private Database $database;
    private AuthService $authService;

    public function __construct(Database $database, AuthService $authService)
    {
        $this->database = $database;
        $this->authService = $authService;
    }

    /**
     * Participant dashboard
     */
    public function dashboard(): void
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        // Get user's registrations
        $registrations = $this->database->query(
            "SELECT r.*, c.name as competition_name, c.competition_date, c.location, cat.name as category_name
             FROM registrations r
             JOIN competitions c ON r.competition_id = c.id
             JOIN categories cat ON r.category_id = cat.id
             WHERE r.user_id = ?
             ORDER BY c.competition_date DESC",
            [$user['id']]
        );

        // Get user's results
        $results = $this->database->query(
            "SELECT res.*, c.name as competition_name, c.competition_date, cat.name as category_name
             FROM results res
             JOIN competitions c ON res.competition_id = c.id
             JOIN categories cat ON res.category_id = cat.id
             WHERE res.user_id = ?
             ORDER BY c.competition_date DESC",
            [$user['id']]
        );

        $this->render('participant/dashboard', [
            'title' => 'Min side',
            'user' => $user,
            'registrations' => $registrations,
            'results' => $results
        ]);
    }

    /**
     * User profile page
     */
    public function profile(): void
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $this->render('participant/profile', [
            'title' => 'Min profil',
            'user' => $user
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(): void
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /participant/profile');
            exit;
        }

        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'address' => trim($_POST['address'] ?? '')
        ];

        // Optional password change
        if (!empty($_POST['password'])) {
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $_SESSION['error'] = 'Passordene stemmer ikke overens';
                header('Location: /participant/profile');
                exit;
            }
            $data['password'] = $_POST['password'];
        }

        try {
            if ($this->authService->updateProfile($user['id'], $data)) {
                $_SESSION['success'] = 'Profil oppdatert!';
            } else {
                $_SESSION['error'] = 'Ingen endringer ble gjort';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /participant/profile');
        exit;
    }

    /**
     * User's registrations
     */
    public function registrations(): void
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $registrations = $this->database->query(
            "SELECT r.*, c.name as competition_name, c.competition_date, c.location, c.registration_end,
                    cat.name as category_name
             FROM registrations r
             JOIN competitions c ON r.competition_id = c.id
             JOIN categories cat ON r.category_id = cat.id
             WHERE r.user_id = ?
             ORDER BY c.competition_date DESC",
            [$user['id']]
        );

        $this->render('participant/registrations', [
            'title' => 'Mine påmeldinger',
            'registrations' => $registrations
        ]);
    }

    /**
     * Register for competition
     */
    public function registerForCompetition(array $params): void
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $competitionId = $params['competitionId'] ?? null;
        if (!$competitionId) {
            $_SESSION['error'] = 'Ugyldig stevne';
            header('Location: /competitions');
            exit;
        }

        // Check if competition exists and registration is open
        $competition = $this->database->queryOne(
            "SELECT * FROM competitions WHERE id = ? AND is_published = 1",
            [$competitionId]
        );

        if (!$competition) {
            $_SESSION['error'] = 'Stevne ikke funnet';
            header('Location: /competitions');
            exit;
        }

        $now = date('Y-m-d H:i:s');
        if ($now < $competition['registration_start'] || $now > $competition['registration_end']) {
            $_SESSION['error'] = 'Påmelding er ikke åpen for dette stevnet';
            header('Location: /competitions');
            exit;
        }

        // Check if already registered
        $existing = $this->database->queryOne(
            "SELECT id FROM registrations WHERE user_id = ? AND competition_id = ?",
            [$user['id'], $competitionId]
        );

        if ($existing) {
            $_SESSION['error'] = 'Du er allerede påmeldt dette stevnet';
            header('Location: /competitions');
            exit;
        }

        // Get available categories
        $categories = $this->database->query(
            "SELECT * FROM categories WHERE is_active = 1 ORDER BY name"
        );

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryId = $_POST['category_id'] ?? null;
            $notes = trim($_POST['notes'] ?? '');

            if (!$categoryId) {
                $_SESSION['error'] = 'Velg en kategori';
            } else {
                try {
                    $this->database->execute(
                        "INSERT INTO registrations (user_id, competition_id, category_id, notes) VALUES (?, ?, ?, ?)",
                        [$user['id'], $competitionId, $categoryId, $notes]
                    );

                    $_SESSION['success'] = 'Påmelding vellykket!';
                    header('Location: /participant/registrations');
                    exit;
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Feil under påmelding: ' . $e->getMessage();
                }
            }
        }

        $this->render('participant/register-competition', [
            'title' => 'Meld deg på stevne',
            'competition' => $competition,
            'categories' => $categories
        ]);
    }

    /**
     * Unregister from competition
     */
    public function unregisterFromCompetition(array $params): void
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $competitionId = $params['competitionId'] ?? null;
        if (!$competitionId) {
            $_SESSION['error'] = 'Ugyldig stevne';
            header('Location: /participant/registrations');
            exit;
        }

        // Check if registration exists
        $registration = $this->database->queryOne(
            "SELECT * FROM registrations WHERE user_id = ? AND competition_id = ?",
            [$user['id'], $competitionId]
        );

        if (!$registration) {
            $_SESSION['error'] = 'Du er ikke påmeldt dette stevnet';
            header('Location: /participant/registrations');
            exit;
        }

        // Check if it's still possible to unregister (before competition date)
        $competition = $this->database->queryOne(
            "SELECT * FROM competitions WHERE id = ?",
            [$competitionId]
        );

        if ($competition && strtotime($competition['competition_date']) <= time()) {
            $_SESSION['error'] = 'Kan ikke melde seg av etter stevnedato';
            header('Location: /participant/registrations');
            exit;
        }

        try {
            $this->database->execute(
                "DELETE FROM registrations WHERE user_id = ? AND competition_id = ?",
                [$user['id'], $competitionId]
            );

            $_SESSION['success'] = 'Avmelding vellykket!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Feil under avmelding: ' . $e->getMessage();
        }

        header('Location: /participant/registrations');
        exit;
    }

    /**
     * User's results
     */
    public function myResults(): void
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $results = $this->database->query(
            "SELECT res.*, c.name as competition_name, c.competition_date, cat.name as category_name
             FROM results res
             JOIN competitions c ON res.competition_id = c.id
             JOIN categories cat ON res.category_id = cat.id
             WHERE res.user_id = ?
             ORDER BY c.competition_date DESC",
            [$user['id']]
        );

        $this->render('participant/results', [
            'title' => 'Mine resultater',
            'results' => $results
        ]);
    }

    /**
     * Render a view
     */
    private function render(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/{$view}.php";
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "View not found: {$view}";
        }
    }
}
