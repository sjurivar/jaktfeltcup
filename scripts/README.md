# Scripts Directory

This directory contains organized scripts for database management and maintenance.

## Directory Structure

### `/setup/`
Scripts for initial database setup and configuration:
- `setup_database.php` - Create database and import schema
- `setup_sample_data.php` - Import sample/test data

### `/migration/`
Scripts for database migrations and structure updates:
- `migrate_add_test_data_column.php` - Add is_test_data column to all tables
- `check_database_structure.php` - Verify database structure

### `/debug/`
Scripts for debugging and data inspection:
- `check_database_tables.php` - List all tables and row counts
- `check_imported_data.php` - Verify imported sample data

## Usage

All scripts can be accessed through the [Database Admin Panel](../../admin/database/) or run directly from their respective directories.

## Notes

- All scripts use the database configuration from `config/config.php`
- Scripts are designed to be run in a web browser for easy access
- Error handling is included for common database issues
- Scripts provide clear feedback on success/failure status
