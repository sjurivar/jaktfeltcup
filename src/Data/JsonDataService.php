<?php

namespace Jaktfeltcup\Data;

/**
 * JSON Data Service
 * 
 * Handles data operations using JSON files instead of database.
 * Useful for development, testing, and demo purposes.
 */
class JsonDataService
{
    private string $dataFile;
    private ?array $data = null;

    public function __construct(string $dataFile = null)
    {
        $this->dataFile = $dataFile ?? __DIR__ . '/../../data/sample_data.json';
    }

    /**
     * Load data from JSON file
     */
    private function loadData(): array
    {
        if ($this->data === null) {
            if (!file_exists($this->dataFile)) {
                throw new \Exception("Data file not found: {$this->dataFile}");
            }
            
            $json = file_get_contents($this->dataFile);
            $this->data = json_decode($json, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Invalid JSON data: " . json_last_error_msg());
            }
        }
        
        return $this->data;
    }

    /**
     * Get all records from a table
     */
    public function getAll(string $table): array
    {
        $data = $this->loadData();
        return $data[$table] ?? [];
    }

    /**
     * Get single record by ID
     */
    public function getById(string $table, int $id): ?array
    {
        $records = $this->getAll($table);
        
        foreach ($records as $record) {
            if ($record['id'] == $id) {
                return $record;
            }
        }
        
        return null;
    }

    /**
     * Find records by criteria
     */
    public function find(string $table, array $criteria): array
    {
        $records = $this->getAll($table);
        $results = [];
        
        foreach ($records as $record) {
            $match = true;
            foreach ($criteria as $key => $value) {
                if (!isset($record[$key]) || $record[$key] != $value) {
                    $match = false;
                    break;
                }
            }
            if ($match) {
                $results[] = $record;
            }
        }
        
        return $results;
    }

    /**
     * Find single record by criteria
     */
    public function findOne(string $table, array $criteria): ?array
    {
        $results = $this->find($table, $criteria);
        return empty($results) ? null : $results[0];
    }

    /**
     * Get competitions with results count
     */
    public function getCompetitionsWithResults(): array
    {
        $competitions = $this->getAll('competitions');
        $results = $this->getAll('results');
        
        foreach ($competitions as &$competition) {
            $competition['result_count'] = 0;
            foreach ($results as $result) {
                if ($result['competition_id'] == $competition['id']) {
                    $competition['result_count']++;
                }
            }
        }
        
        return $competitions;
    }

    /**
     * Get standings for a season
     */
    public function getStandings(int $seasonId): array
    {
        $users = $this->getAll('users');
        $results = $this->getAll('results');
        $competitions = $this->getAll('competitions');
        
        // Filter results by season
        $seasonResults = [];
        foreach ($results as $result) {
            foreach ($competitions as $competition) {
                if ($competition['id'] == $result['competition_id'] && $competition['season_id'] == $seasonId) {
                    $seasonResults[] = $result;
                }
            }
        }
        
        // Calculate standings
        $standings = [];
        foreach ($users as $user) {
            if ($user['role'] === 'participant') {
                $totalPoints = 0;
                $competitionsEntered = 0;
                $totalScore = 0;
                
                foreach ($seasonResults as $result) {
                    if ($result['user_id'] == $user['id']) {
                        $totalPoints += $result['points_awarded'];
                        $competitionsEntered++;
                        $totalScore += $result['score'];
                    }
                }
                
                if ($competitionsEntered > 0) {
                    $standings[] = [
                        'id' => $user['id'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'total_points' => $totalPoints,
                        'competitions_entered' => $competitionsEntered,
                        'avg_score' => $totalScore / $competitionsEntered
                    ];
                }
            }
        }
        
        // Sort by total points
        usort($standings, function($a, $b) {
            if ($a['total_points'] == $b['total_points']) {
                return $b['competitions_entered'] - $a['competitions_entered'];
            }
            return $b['total_points'] - $a['total_points'];
        });
        
        return $standings;
    }

    /**
     * Get user's registrations
     */
    public function getUserRegistrations(int $userId): array
    {
        $registrations = $this->getAll('registrations');
        $competitions = $this->getAll('competitions');
        $categories = $this->getAll('categories');
        
        $userRegistrations = [];
        foreach ($registrations as $registration) {
            if ($registration['user_id'] == $userId) {
                $competition = $this->getById('competitions', $registration['competition_id']);
                $category = $this->getById('categories', $registration['category_id']);
                
                if ($competition && $category) {
                    $userRegistrations[] = array_merge($registration, [
                        'competition_name' => $competition['name'],
                        'competition_date' => $competition['competition_date'],
                        'location' => $competition['location'],
                        'registration_end' => $competition['registration_end'],
                        'category_name' => $category['name']
                    ]);
                }
            }
        }
        
        return $userRegistrations;
    }

    /**
     * Get user's results
     */
    public function getUserResults(int $userId): array
    {
        $results = $this->getAll('results');
        $competitions = $this->getAll('competitions');
        $categories = $this->getAll('categories');
        
        $userResults = [];
        foreach ($results as $result) {
            if ($result['user_id'] == $userId) {
                $competition = $this->getById('competitions', $result['competition_id']);
                $category = $this->getById('categories', $result['category_id']);
                
                if ($competition && $category) {
                    $userResults[] = array_merge($result, [
                        'competition_name' => $competition['name'],
                        'competition_date' => $competition['competition_date'],
                        'category_name' => $category['name']
                    ]);
                }
            }
        }
        
        return $userResults;
    }

    /**
     * Get competitions with registration count
     */
    public function getCompetitionsWithRegistrations(): array
    {
        $competitions = $this->getAll('competitions');
        $registrations = $this->getAll('registrations');
        $users = $this->getAll('users');
        
        foreach ($competitions as &$competition) {
            $competition['registered_count'] = 0;
            $competition['organizer_name'] = '';
            
            // Count registrations
            foreach ($registrations as $registration) {
                if ($registration['competition_id'] == $competition['id'] && $registration['status'] === 'confirmed') {
                    $competition['registered_count']++;
                }
            }
            
            // Get organizer name
            $organizer = $this->getById('users', $competition['organizer_id']);
            if ($organizer) {
                $competition['organizer_name'] = $organizer['first_name'] . ' ' . $organizer['last_name'];
            }
        }
        
        return $competitions;
    }
}
