# Configuration

## Setup

1. Copy `config.example.php` to `config.php`:
   ```bash
   cp config/config.example.php config/config.php
   ```

2. Update the configuration values in `config.php` with your settings:
   - Database credentials
   - Application URL
   - Email settings (if needed)

## Files

- `config.example.php` - Example configuration (committed to Git)
- `config.php` - Your actual configuration (ignored by Git)
- `config.local.php` - Local development overrides (ignored by Git)
- `config.production.php` - Production overrides (ignored by Git)

## Environment-specific Configuration

For different environments, you can create:
- `config.local.php` for local development
- `config.production.php` for production

These files will override settings from `config.php` if they exist.
