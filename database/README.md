# Database Files

## Files

- `schema.sql` - Main database schema with all tables and structure
- `sample_data.sql` - Sample/test data for development and testing

## Setup

### 1. Create Database and Schema
```bash
# Run the main setup script
php setup_database.php
```

### 2. Import Sample Data (Optional)
```bash
# Run the sample data script
php setup_sample_data.php
```

### 3. Migration (If you have existing data)
If you get an error about missing `is_test_data` column, run:
```bash
# Add the is_test_data column to existing tables
php migrate_add_test_data_column.php
```

## Sample Data

The sample data includes:

### Test Users
- **Admin:** `testadmin` / `password`
- **Organizer:** `testorganizer` / `password`  
- **Participants:** `testdeltaker1` to `testdeltaker10` / `password`

### Test Competitions
- Vårstevnet 2024 (Test)
- Sommerstevnet 2024 (Test)
- Høststevnet 2024 (Test)
- Vinterstevnet 2024 (Test)

### Test Categories
- Senior (Test)
- Junior (Test)
- Veteran (Test)
- Dame (Test)

### Test Data Marking

All sample data is marked with `is_test_data = TRUE` to distinguish it from real data. This allows you to:

- Filter out test data in production queries
- Easily clean up test data when needed
- Keep test and real data separate

### Cleaning Test Data

To remove all test data:
```sql
DELETE FROM jaktfelt_audit_log WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_notifications WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_season_point_systems WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_point_rules WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_point_systems WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_results WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_registrations WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_competition_categories WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_competitions WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_categories WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_users WHERE is_test_data = TRUE;
DELETE FROM jaktfelt_seasons WHERE is_test_data = TRUE;
```

## Table Structure

All tables use the `jaktfelt_` prefix and include:
- Standard fields (id, created_at, updated_at)
- `is_test_data` field for marking test data
- Appropriate indexes for performance
- Foreign key constraints for data integrity
