# Installer PHPMailer Manuelt (Uten Composer)

## Metode 1: Last ned fra GitHub

```bash
# Gå til prosjektmappen
cd E:\xampp\htdocs\jaktfeltcup

# Opprett vendor mappe
mkdir vendor
mkdir vendor\phpmailer
mkdir vendor\phpmailer\phpmailer

# Last ned PHPMailer
cd vendor\phpmailer\phpmailer
git clone https://github.com/PHPMailer/PHPMailer.git .

# Eller last ned ZIP fra: https://github.com/PHPMailer/PHPMailer/archive/master.zip
# Pakk ut i vendor/phpmailer/phpmailer/
```

## Metode 2: Last ned ZIP fil

1. Gå til: https://github.com/PHPMailer/PHPMailer/archive/master.zip
2. Last ned ZIP filen
3. Pakk ut i `vendor/phpmailer/phpmailer/`
4. Strukturen skal være:
   ```
   vendor/
   └── phpmailer/
       └── phpmailer/
           ├── src/
           │   ├── PHPMailer.php
           │   ├── SMTP.php
           │   └── Exception.php
           └── ...
   ```

## Metode 3: Bruk kun PHP mail() (Enklest)

Hvis du ikke trenger SMTP, kan du bruke kun PHP mail() funksjonen:

```php
// I config.php
$mail_config = [
    'from_address' => 'noreply@jaktfeltcup.no',
    'from_name' => 'Nasjonal 15m Jaktfeltcup'
    // Ingen SMTP-innstillinger nødvendig
];
```

## Test uten PHPMailer

Gå til: `http://localhost/jaktfeltcup/test_email_no_composer.php`

Denne testen fungerer med eller uten PHPMailer!

## Fordeler med hver metode:

### PHP mail() (Enklest)
- ✅ Ingen ekstra installasjon
- ✅ Fungerer umiddelbart
- ❌ Kan bli filtrert som spam
- ❌ Ingen SMTP støtte

### PHPMailer (Anbefalt)
- ✅ Bedre leveringsrate
- ✅ SMTP støtte
- ✅ Bedre feilhåndtering
- ❌ Krever installasjon

## XAMPP Mail Setup

For at mail() skal fungere i XAMPP:

1. **Åpne `php.ini`** (vanligvis i `C:\xampp\php\php.ini`)
2. **Finn `[mail function]` seksjonen**
3. **Konfigurer:**
   ```ini
   [mail function]
   SMTP = smtp.gmail.com
   smtp_port = 587
   sendmail_from = din@epost.no
   ```
4. **Restart Apache**

Eller bruk en e-post service som:
- MailHog (for testing)
- Mailtrap (for testing)
- Gmail SMTP
- Din egen SMTP server
