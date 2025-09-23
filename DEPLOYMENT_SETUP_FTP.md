# FTP Deployment Setup for hjellum.net

## GitHub Repository Setup

### 1. Repository Variables

Gå til GitHub repository → Settings → Secrets and variables → Actions → Variables

Legg til:
```
FTP_SERVER=hjellum.net
```

### 2. Repository Secrets

Gå til GitHub repository → Settings → Secrets and variables → Actions → Secrets

Legg til:
```
FTP_USER=ditt_brukernavn
FTP_PASSWORD=ditt_passord
```

## Deployment Workflow

### Branch Strategy

- **`main` branch** → Production deployment til `/home/username/public_html/jaktfeltcup/`
- **`staging` branch** → Staging deployment til `/home/username/public_html/staging/jaktfeltcup/`

### Automatic Deployment

```bash
# Deploy to production
git checkout main
git add .
git commit -m "Deploy to production"
git push origin main

# Deploy to staging
git checkout staging
git add .
git commit -m "Deploy to staging"
git push origin staging
```

## Server Setup

### 1. Directory Structure

```bash
# Production
/home/username/public_html/jaktfeltcup/

# Staging
/home/username/public_html/staging/jaktfeltcup/
```

### 2. Database Setup

```bash
# Opprett database
mysql -u root -p -e "CREATE DATABASE jaktfeltcup;"

# Importer schema
mysql -u root -p jaktfeltcup < database/schema.sql

# Opprett bruker
mysql -u root -p -e "CREATE USER 'jaktfeltcup'@'localhost' IDENTIFIED BY 'strong_password';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON jaktfeltcup.* TO 'jaktfeltcup'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"
```

### 3. File Permissions

```bash
# Sett korrekte permissions
chmod -R 755 /home/username/public_html/jaktfeltcup
chmod 644 /home/username/public_html/jaktfeltcup/*.php
chmod 644 /home/username/public_html/jaktfeltcup/.htaccess

# Sett ownership
chown -R username:username /home/username/public_html/jaktfeltcup
```

## Configuration

### Production Config

Filen `config/config.production.php` blir automatisk brukt for main branch:

```php
// Database configuration
$db_config = [
    'host' => 'localhost',
    'name' => 'jaktfeltcup',
    'user' => 'jaktfeltcup',
    'password' => 'your_secure_password'
];

// Application configuration
$app_config = [
    'name' => 'Jaktfeltcup',
    'url' => 'https://hjellum.net/jaktfeltcup',
    'base_url' => '/jaktfeltcup',
    'debug' => false,
    'data_source' => 'database'
];
```

### Staging Config

For staging branch, rediger `config/config.php`:

```php
// Database configuration
$db_config = [
    'host' => 'localhost',
    'name' => 'jaktfeltcup_staging',
    'user' => 'jaktfeltcup',
    'password' => 'your_secure_password'
];

// Application configuration
$app_config = [
    'name' => 'Jaktfeltcup (Staging)',
    'url' => 'https://hjellum.net/staging/jaktfeltcup',
    'base_url' => '/staging/jaktfeltcup',
    'debug' => true,
    'data_source' => 'json' // Bruk JSON for staging
];
```

## Testing

### 1. Test Production

```bash
# Test hjemmeside
curl -f https://hjellum.net/jaktfeltcup/

# Test resultater
curl -f https://hjellum.net/jaktfeltcup/results

# Test sammenlagt
curl -f https://hjellum.net/jaktfeltcup/standings
```

### 2. Test Staging

```bash
# Test staging
curl -f https://hjellum.net/staging/jaktfeltcup/
```

## Troubleshooting

### Common Issues

1. **FTP Connection Failed**
   - Sjekk FTP credentials
   - Sjekk at FTP server er tilgjengelig
   - Test: `ftp hjellum.net`

2. **Permission Denied**
   ```bash
   chmod -R 755 /home/username/public_html/jaktfeltcup
   chown -R username:username /home/username/public_html/jaktfeltcup
   ```

3. **Database Connection Failed**
   - Sjekk database credentials
   - Sjekk at MySQL kjører
   - Test: `mysql -u jaktfeltcup -p jaktfeltcup`

4. **404 Errors**
   - Sjekk at `.htaccess` er lastet opp
   - Sjekk at mod_rewrite er aktivert
   - Test: `curl -I https://hjellum.net/jaktfeltcup/`

### Logs

```bash
# Apache error logs
tail -f /var/log/apache2/error.log

# PHP error logs
tail -f /var/log/php/error.log

# FTP logs
tail -f /var/log/vsftpd.log
```

## Security

### Checklist

- [ ] FTP credentials er sikre
- [ ] Database credentials er sikre
- [ ] File permissions er korrekte
- [ ] .htaccess er konfigurert
- [ ] HTTPS er aktivert
- [ ] Error reporting er deaktivert i produksjon
- [ ] Backup er satt opp

### Backup

```bash
# Opprett backup script
cat > backup.sh << 'EOF'
#!/bin/bash
DATE=$(date +%Y%m%d-%H%M%S)
tar -czf /home/username/backup/jaktfeltcup-$DATE.tar.gz /home/username/public_html/jaktfeltcup/
mysqldump -u jaktfeltcup -p jaktfeltcup > /home/username/backup/jaktfeltcup-$DATE.sql
EOF

chmod +x backup.sh
```

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

# Sjekk FTP status
systemctl status vsftpd
```
