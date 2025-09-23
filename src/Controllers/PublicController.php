<?php

namespace Jaktfeltcup\Controllers;

use Jaktfeltcup\Core\Database;
use Jaktfeltcup\Core\Config;

/**
 * Public Controller
 * 
 * Handles public-facing pages that don't require authentication.
 */
class PublicController
{
    private Database $database;
    private Config $config;

    public function __construct(Database $database, Config $config)
    {
        $this->database = $database;
        $this->config = $config;
    }

    /**
     * Home page
     */
    public function home(): void
    {
        $this->render('public/home', [
            'title' => 'Jaktfeltcup',
            'description' => 'Administrasjonssystem for skyteøvelse'
        ]);
    }

    /**
     * Display results for all competitions
     */
    public function results(): void
    {
        $seasonId = $_GET['season'] ?? $this->getCurrentSeasonId();
        
        $competitions = $this->database->query(
            "SELECT c.*, s.name as season_name 
             FROM competitions c 
             JOIN seasons s ON c.season_id = s.id 
             WHERE s.id = ? AND c.is_published = 1 
             ORDER BY c.competition_date DESC",
            [$seasonId]
        );

        $this->render('public/results', [
            'title' => 'Resultater',
            'competitions' => $competitions,
            'seasonId' => $seasonId
        ]);
    }

    /**
     * Display results for specific competition
     */
    public function competitionResults(array $params): void
    {
        $competitionId = $params['competitionId'];
        
        $competition = $this->database->queryOne(
            "SELECT c.*, s.name as season_name 
             FROM competitions c 
             JOIN seasons s ON c.season_id = s.id 
             WHERE c.id = ? AND c.is_published = 1",
            [$competitionId]
        );

        if (!$competition) {
            $this->handleNotFound();
            return;
        }

        $results = $this->database->query(
            "SELECT r.*, u.first_name, u.last_name, cat.name as category_name
             FROM results r
             JOIN users u ON r.user_id = u.id
             JOIN categories cat ON r.category_id = cat.id
             WHERE r.competition_id = ?
             ORDER BY r.score DESC, r.position ASC",
            [$competitionId]
        );

        $this->render('public/competition-results', [
            'title' => 'Resultater - ' . $competition['name'],
            'competition' => $competition,
            'results' => $results
        ]);
    }

    /**
     * Display standings/leaderboard
     */
    public function standings(): void
    {
        $seasonId = $_GET['season'] ?? $this->getCurrentSeasonId();
        
        $standings = $this->database->query(
            "SELECT u.id, u.first_name, u.last_name, 
                    SUM(r.points_awarded) as total_points,
                    COUNT(r.id) as competitions_entered
             FROM users u
             LEFT JOIN results r ON u.id = r.user_id
             LEFT JOIN competitions c ON r.competition_id = c.id
             LEFT JOIN seasons s ON c.season_id = s.id
             WHERE s.id = ? AND r.points_awarded > 0
             GROUP BY u.id, u.first_name, u.last_name
             ORDER BY total_points DESC, competitions_entered DESC",
            [$seasonId]
        );

        $this->render('public/standings', [
            'title' => 'Sammenlagt',
            'standings' => $standings,
            'seasonId' => $seasonId
        ]);
    }

    /**
     * Display upcoming competitions
     */
    public function competitions(): void
    {
        $seasonId = $_GET['season'] ?? $this->getCurrentSeasonId();
        
        $competitions = $this->database->query(
            "SELECT c.*, s.name as season_name,
                    COUNT(reg.id) as registered_count
             FROM competitions c
             JOIN seasons s ON c.season_id = s.id
             LEFT JOIN registrations reg ON c.id = reg.competition_id AND reg.status = 'confirmed'
             WHERE s.id = ? AND c.is_published = 1
             GROUP BY c.id
             ORDER BY c.competition_date ASC",
            [$seasonId]
        );

        $this->render('public/competitions', [
            'title' => 'Stevner',
            'competitions' => $competitions,
            'seasonId' => $seasonId
        ]);
    }

    /**
     * Offline sync endpoint
     */
    public function offlineSync(): void
    {
        $lastSync = $_GET['last_sync'] ?? null;
        
        $syncData = $this->database->query(
            "SELECT * FROM offline_sync 
             WHERE sync_status = 'pending' 
             " . ($lastSync ? "AND created_at > ?" : ""),
            $lastSync ? [$lastSync] : []
        );

        $this->jsonResponse($syncData);
    }

    /**
     * Handle offline sync data
     */
    public function offlineSyncData(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            $this->jsonResponse(['error' => 'Invalid data'], 400);
            return;
        }

        // Process offline sync data
        foreach ($data as $item) {
            $this->processOfflineSyncItem($item);
        }

        $this->jsonResponse(['status' => 'success']);
    }

    /**
     * Get current active season ID
     */
    private function getCurrentSeasonId(): int
    {
        $season = $this->database->queryOne(
            "SELECT id FROM seasons WHERE is_active = 1 ORDER BY year DESC LIMIT 1"
        );
        
        return $season ? $season['id'] : 1;
    }

    /**
     * Process offline sync item
     */
    private function processOfflineSyncItem(array $item): void
    {
        // Implementation for processing offline sync data
        // This would handle creating/updating records from offline changes
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

    /**
     * Send JSON response
     */
    private function jsonResponse(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Handle 404 Not Found
     */
    private function handleNotFound(): void
    {
        http_response_code(404);
        $this->render('errors/404', ['title' => 'Siden ikke funnet']);
    }
}
