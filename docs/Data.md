### Data Service

The JSON data service provides a file-based alternative to the database for development/testing.

---

### JsonDataService (`Jaktfeltcup\Data\JsonDataService`)

Constructor:
- `__construct(string $dataFile = null)` — Defaults to `data/sample_data.json`.

Public API:
- `getAll(string $table): array`
- `getById(string $table, int $id): ?array`
- `find(string $table, array $criteria): array`
- `findOne(string $table, array $criteria): ?array`
- `getCompetitionsWithResults(): array`
- `getStandings(int $seasonId): array`
- `getUserRegistrations(int $userId): array`
- `getUserResults(int $userId): array`
- `getCompetitionsWithRegistrations(): array`

Example:
```php
use Jaktfeltcup\Data\JsonDataService;

$data = new JsonDataService(__DIR__ . '/../data/sample_data.json');
$competitions = $data->getCompetitionsWithRegistrations();
```

Data format reference: see `data/sample_data.json` for expected table keys such as `users`, `competitions`, `categories`, `results`, `registrations`.

