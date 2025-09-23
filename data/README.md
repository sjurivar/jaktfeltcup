# Data Configuration

## JSON Data Source

The application can use JSON files instead of a database for development and demo purposes.

### Configuration

In `config/config.php`, set:

```php
$app_config = [
    'data_source' => 'json' // 'json' or 'database'
];
```

### Sample Data

The `data/sample_data.json` file contains:

- **2 seasons** (2023, 2024)
- **4 competitions** (2 per season)
- **10 users** (1 admin, 1 organizer, 8 participants)
- **3 categories** (Senior, Junior, Veteran)
- **Realistic results** with points and standings
- **Registration data** for all competitions

### Switching Data Sources

**To use JSON data:**
```php
'data_source' => 'json'
```

**To use database:**
```php
'data_source' => 'database'
```

### Benefits of JSON Data

1. **No database setup required** - works immediately
2. **Easy to modify** - edit JSON file directly
3. **Version control friendly** - track changes in Git
4. **Demo ready** - pre-populated with realistic data
5. **Development friendly** - no database dependencies

### Adding More Data

Edit `data/sample_data.json` to add:
- More users
- Additional competitions
- New categories
- More results
- Different seasons

### Data Structure

The JSON file follows the same structure as the database schema:

```json
{
  "seasons": [...],
  "users": [...],
  "competitions": [...],
  "results": [...],
  "registrations": [...],
  "categories": [...],
  "point_systems": [...],
  "point_rules": [...]
}
```

### Production Use

For production, switch to database:

1. Set `data_source` to `'database'`
2. Set up MySQL database
3. Import `database/schema.sql`
4. Configure database credentials

The application will automatically use the appropriate data source based on configuration.
