# Deployment Setup for hjellum.net

## GitHub Actions Setup

### 1. Repository Secrets

Gå til GitHub repository → Settings → Secrets and variables → Actions

Legg til disse secrets:

```
HOST=hjellum.net
USERNAME=ditt_brukernavn
SSH_KEY=ditt_private_ssh_key
PORT=22 (hvis ikke standard)
```

### 2. SSH Key Setup

**Generer SSH key:**
```bash
ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
```

**Legg til public key til server:**
```bash
# Kopier public key til server
ssh-copy-id -i ~/.ssh/id_rsa.pub username@hjellum.net

# Eller manuelt:
cat ~/.ssh/id_rsa.pub >> ~/.ssh/authorized_keys
```

**Legg til private key som GitHub secret:**
```bash
cat ~/.ssh/id_rsa
# Kopier hele innholdet og legg til som SSH_KEY secret
```

### 3. Server Setup

**På hjellum.net server:**

```bash
# Opprett backup directory
mkdir -p /home/username/backup

# Sett opp database (hvis ikke allerede gjort)
mysql -u root -p -e "CREATE DATABASE jaktfeltcup;"
mysql -u root -p jaktfeltcup < database/schema.sql

# Sett opp bruker for appen
mysql -u root -p -e "CREATE USER 'jaktfeltcup'@'localhost' IDENTIFIED BY 'strong_password';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON jaktfeltcup.* TO 'jaktfeltcup'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"
```

### 4. Production Configuration

**Rediger `config/config.production.php`:**

```php
// Database configuration
$db_config = [
    'host' => 'localhost',
    'name' => 'jaktfeltcup',
    'user' => 'jaktfeltcup',
    'password' => 'strong_password'
];

// Application configuration
$app_config = [
    'name' => 'Jaktfeltcup',
    'url' => 'https://hjellum.net/jaktfeltcup',
    'base_url' => '/jaktfeltcup', // eller '' for rot-domene
    'debug' => false,
    'data_source' => 'database' // Bruk database i produksjon
];
```

## Manual Deployment

### 1. Using deploy.sh script

```bash
# Gjør scriptet kjørbart
chmod +x deploy.sh

# Kjør deployment
./deploy.sh
```

### 2. Manual upload

```bash
# Opprett deployment package
mkdir deploy
cp -r views src config data handlers database deploy/
cp index.php .htaccess README.md deploy/
cp config/config.production.php deploy/config/config.php

# Upload til server
rsync -avz --delete deploy/ username@hjellum.net:/home/username/public_html/jaktfeltcup/

# Sett permissions
ssh username@hjellum.net "chmod -R 755 /home/username/public_html/jaktfeltcup"
```

## Testing Deployment

### 1. Test basic functionality

```bash
# Test hjemmeside
curl -f https://hjellum.net/jaktfeltcup/

# Test resultater
curl -f https://hjellum.net/jaktfeltcup/results

# Test sammenlagt
curl -f https://hjellum.net/jaktfeltcup/standings
```

### 2. Test database connection

```bash
# Test database
mysql -u jaktfeltcup -p jaktfeltcup -e "SELECT COUNT(*) FROM users;"
```

### 3. Test JSON data (development)

```bash
# Hvis du vil teste med JSON data først
# Endre i config/config.php:
'data_source' => 'json'
```

## Troubleshooting

### Common Issues

1. **Permission denied**
   ```bash
   chmod -R 755 /home/username/public_html/jaktfeltcup
   chown -R username:username /home/username/public_html/jaktfeltcup
   ```

2. **Database connection failed**
   - Sjekk database credentials
   - Sjekk at MySQL kjører
   - Test tilkobling: `mysql -u jaktfeltcup -p jaktfeltcup`

3. **404 errors**
   - Sjekk at `.htaccess` er lastet opp
   - Sjekk at mod_rewrite er aktivert
   - Test: `curl -I https://hjellum.net/jaktfeltcup/`

4. **PHP errors**
   - Sjekk PHP error logs
   - Sjekk at alle filer er lastet opp
   - Test: `php -l index.php`

### Logs

```bash
# Apache error logs
tail -f /var/log/apache2/error.log

# PHP error logs
tail -f /var/log/php/error.log

# Application logs (hvis implementert)
tail -f /home/username/public_html/jaktfeltcup/logs/app.log
```

## Security Checklist

- [ ] Database credentials er sikre
- [ ] SSH key er sikker
- [ ] File permissions er korrekte
- [ ] .htaccess er konfigurert
- [ ] HTTPS er aktivert
- [ ] Backup er satt opp
- [ ] Error reporting er deaktivert i produksjon

## Monitoring

```bash
# Sjekk disk usage
df -h

# Sjekk memory usage
free -h

# Sjekk Apache status
systemctl status apache2

# Sjekk MySQL status
systemctl status mysql
```
