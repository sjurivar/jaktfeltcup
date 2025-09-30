# Jaktfeltcup Setup Guide

## 🚀 **Installasjon**

### **1. Klon repository**
```bash
git clone <repository-url>
cd jaktfeltcup
```

### **2. Konfigurer database**
```bash
# Kopier eksempel-konfigurasjon
cp config/config.example.php config/config.php

# Rediger config.php med dine opplysninger
nano config/config.php
```

### **3. Sett opp database**
```bash
# Importer database-struktur
mysql -u root -p jaktfeltcup < database/schema.sql

# Importer eksempel-data (valgfritt)
mysql -u root -p jaktfeltcup < database/sample_data.sql
```

### **4. Konfigurer e-post (valgfritt)**

#### **Alternativ 1: Mailjet (anbefalt)**
1. Opprett konto på [mailjet.com](https://www.mailjet.com)
2. Få API-nøkler fra dashboard
3. Oppdater `$mailjet_config` i `config.php`

#### **Alternativ 2: SMTP**
1. Oppdater `$mail_config` i `config.php`
2. Konfigurer SMTP-server

### **5. Test installasjonen**
```bash
# Test database-tilkobling
php scripts/debug/test_database.php

# Test e-post (hvis konfigurert)
php test_mailjet.php
```

## 🔧 **Konfigurasjon**

### **config.php**
```php
// Database
$db_config = [
    'host' => 'localhost',
    'name' => 'jaktfeltcup',
    'user' => 'your_user',
    'password' => 'your_password'
];

// App
$app_config = [
    'url' => 'http://localhost/jaktfeltcup',
    'base_url' => '/jaktfeltcup', // '' for production
    'debug' => true
];

// E-post
$mailjet_config = [
    'api_key' => 'your_api_key',
    'secret_key' => 'your_secret_key'
];
```

## 🚨 **Sikkerhet**

- **Aldri commit `config.php`** - den inneholder sensitive data
- **Bruk `config.example.php`** som mal
- **Sett riktige filrettigheter** på serveren
- **Bruk HTTPS** i produksjon

## 📁 **Filstruktur**

```
jaktfeltcup/
├── config/
│   ├── config.example.php  # Eksempel-konfigurasjon
│   └── config.php          # Din konfigurasjon (ikke i Git)
├── src/                    # Applikasjonskode
├── views/                  # HTML-templates
├── assets/                 # Statiske filer
└── scripts/               # Hjelpescripts
```

## 🆘 **Feilsøking**

### **Database-problemer**
```bash
# Test tilkobling
php scripts/debug/test_database.php

# Sjekk tabeller
php scripts/debug/check_database_structure.php
```

### **E-post-problemer**
```bash
# Test Mailjet
php test_mailjet.php

# Test PHP mail()
php test_php_mail.php
```

### **Logo-problemer**
```bash
# Test logo-sti
php test_logo.php
```

## 📞 **Support**

Se `dokumentasjon/` for detaljert brukerguide.
