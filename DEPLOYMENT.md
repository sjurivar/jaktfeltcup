# Deployment Guide - Jaktfeltcup

## Lokal utvikling

For lokal utvikling med XAMPP:

1. **Konfigurasjon:** Bruk `config/config.php` (standard)
2. **Base URL:** `/jaktfeltcup`
3. **Database:** Lokal MySQL

## Produksjon

For produksjon på web server:

### 1. Konfigurasjon

Kopier produksjonskonfigurasjonen:

```bash
cp config/config.production.php config/config.php
```

Rediger `config/config.php` med dine produksjonsinnstillinger:

```php
// Database
$db_config = [
    'host' => 'localhost',
    'name' => 'jaktfeltcup',
    'user' => 'your_production_user',
    'password' => 'your_production_password'
];

// Base URL for produksjon
$app_config = [
    'base_url' => '', // Tom for rot-domene
    'url' => 'https://yourdomain.com',
    'debug' => false
];
```

### 2. Database setup

```bash
# Opprett database
mysql -u root -p -e "CREATE DATABASE jaktfeltcup;"

# Importer schema
mysql -u root -p jaktfeltcup < database/schema.sql
```

### 3. Filopplasting

Last opp alle filer til web server:

```
public_html/
├── index.php
├── config/
├── views/
├── src/
├── handlers/
├── database/
└── ...
```

### 4. Apache konfigurasjon

Sett opp `.htaccess` for URL rewriting:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

### 5. Sikkerhet

- Sett riktige filrettigheter (644 for filer, 755 for mapper)
- Skjul sensitive filer fra web
- Aktiver HTTPS
- Sett opp backup

## URL-struktur

### Lokal utvikling:
- `http://localhost/jaktfeltcup/`
- `http://localhost/jaktfeltcup/results`
- `http://localhost/jaktfeltcup/login`

### Produksjon:
- `https://yourdomain.com/`
- `https://yourdomain.com/results`
- `https://yourdomain.com/login`

## Miljøvariabler

For avanserte innstillinger, kan du bruke miljøvariabler:

```php
// I config/config.php
$app_config = [
    'base_url' => $_ENV['BASE_URL'] ?? '/jaktfeltcup',
    'debug' => filter_var($_ENV['DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
];
```

## Testing

Etter deployment:

1. Test alle lenker
2. Test innlogging/registrering
3. Test database-tilkobling
4. Test e-post funksjonalitet
5. Test offline-funksjonalitet

## Backup

Sett opp automatisk backup av:
- Database
- Uploadede filer
- Konfigurasjon
