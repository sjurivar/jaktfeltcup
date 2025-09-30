# E-post Setup Guide

## 1. Installer PHPMailer

```bash
composer install
```

Eller hvis composer ikke er installert:

```bash
# Last ned PHPMailer manuelt
mkdir -p vendor/phpmailer/phpmailer
cd vendor/phpmailer/phpmailer
git clone https://github.com/PHPMailer/PHPMailer.git .
```

## 2. Konfigurer SMTP i config.php

```php
$mail_config = [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'din@gmail.com',        // Fyll inn din e-post
    'password' => 'ditt_app_password',    // Fyll inn App Password
    'encryption' => 'tls',
    'from_address' => 'noreply@jaktfeltcup.no',
    'from_name' => 'Nasjonal 15m Jaktfeltcup'
];
```

## 3. Gmail Setup

### For Gmail:
1. Gå til Google Account Settings
2. Aktiver 2-Factor Authentication
3. Generer "App Password" for e-post
4. Bruk App Password i stedet for vanlig passord

### Andre e-post leverandører:
- **Outlook/Hotmail**: smtp-mail.outlook.com, port 587
- **Yahoo**: smtp.mail.yahoo.com, port 587
- **Custom SMTP**: Bruk din egen SMTP server

## 4. Test E-post

Gå til: `http://localhost/jaktfeltcup/test_phpmailer.php`

## 5. Feilsøking

### Vanlige problemer:
- **"Authentication failed"**: Sjekk username/password
- **"Connection refused"**: Sjekk host/port
- **"SSL/TLS error"**: Sjekk encryption setting
- **"PHPMailer not found"**: Kjør `composer install`

### Debug mode:
Aktiver debug i EmailServicePHPMailer.php:
```php
$mail->SMTPDebug = 2; // Enable verbose debug output
```

## 6. Produksjon

For produksjon, bruk:
- Dedikert SMTP server
- Egen e-post domene
- Rate limiting
- E-post templates
- Error logging
