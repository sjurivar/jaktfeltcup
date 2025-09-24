# Admin Panel

This directory contains administrative tools for managing the Jaktfeltcup application.

## Database Admin

The `database/` subdirectory contains tools for managing the database:

### Main Admin Panel
- **`index.php`** - Main admin dashboard with overview and quick actions

### Check Operations
- **`check_tables.php`** - Check which tables exist (old vs new)
- **`check_data.php`** - Check what data is imported
- **`check_structure.php`** - Check if is_test_data columns exist
- **`check_constraints.php`** - Check foreign key constraints

### Setup Operations
- **`setup_database.php`** - Create database and import schema
- **`migrate_add_test_data_column.php`** - Add is_test_data column to all tables
- **`import_sample_data.php`** - Import sample data for testing
- **`import_results.php`** - Import test results

### Dangerous Operations
- **`drop_old_tables.php`** - Drop tables without jaktfelt_ prefix
- **`drop_new_tables.php`** - Drop tables with jaktfelt_ prefix
- **`drop_all_tables.php`** - Drop ALL tables in database
- **`clear_test_data.php`** - Clear all test data (keep structure)

## Usage

1. Navigate to `/admin/database/` in your browser
2. Use the main dashboard to access all operations
3. Follow the recommended workflow:
   - Check current state
   - Fix any issues
   - Import data as needed

## Security

⚠️ **Warning**: These tools have full database access and can permanently delete data. Use with caution and only in development/testing environments.

## Workflow

### Initial Setup
1. `setup_database.php` - Create database and schema
2. `migrate_add_test_data_column.php` - Add test data columns
3. `import_sample_data.php` - Import sample data
4. `import_results.php` - Import test results

### Maintenance
1. `check_tables.php` - Verify table structure
2. `check_data.php` - Verify data integrity
3. `clear_test_data.php` - Clean up test data when needed

### Troubleshooting
1. `check_constraints.php` - Check foreign key issues
2. `drop_old_tables.php` - Remove old tables if needed
3. `setup_database.php` - Recreate database if corrupted
