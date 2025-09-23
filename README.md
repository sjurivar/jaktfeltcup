# Jaktfeltcup - Administrasjonssystem for skyteøvelse

Et moderne web-basert administrasjonssystem for å håndtere skytekonkurranser og jaktfeltcup.

## Funksjoner

### For publikum
- Se resultater fra enkeltstevner og sammenlagt
- Se kommende stevner
- Følg cup-utviklingen

### For deltakere
- Opprett egen bruker og profil
- Meld seg på stevner
- Se egne resultater og plassering
- Rediger egne opplysninger

### For stevnearrangører
- Opprett og administrere stevner
- Registrere resultater
- Legge inn deltakere som ikke har meldt seg på selv
- Redigere resultater til stevnet er låst
- Sende varslinger til deltakere

### For administratorer
- Tildele roller til brukere
- Administrere sesonger og kategorier
- Konfigurere poengsystemer
- Full redigeringsmulighet på alle data
- Sende bulk-varslinger

## Teknisk stack

- **Backend:** PHP 8.1+
- **Database:** MySQL 8.0+
- **Frontend:** Bootstrap 5, Vanilla JavaScript
- **Autentisering:** JWT tokens
- **E-post:** PHPMailer med SMTP
- **SMS:** Twilio integration
- **Offline:** Service Worker + IndexedDB

## Installasjon

### Krav
- PHP 8.1 eller høyere
- MySQL 8.0 eller høyere
- Composer
- Web server (Apache/Nginx)

### Steg for steg

1. **Klon repositoriet**
   ```bash
   git clone <repository-url>
   cd jaktfeltcup
   ```

2. **Installer avhengigheter**
   ```bash
   composer install
   ```

3. **Konfigurer miljøvariabler**
   ```bash
   cp env.example .env
   # Rediger .env med dine innstillinger
   ```

4. **Sett opp database**
   ```bash
   # Opprett database
   mysql -u root -p -e "CREATE DATABASE jaktfeltcup;"
   
   # Importer schema
   mysql -u root -p jaktfeltcup < database/schema.sql
   ```

5. **Konfigurer web server**
   - Sett document root til `public/` mappen
   - Aktiver URL rewriting

6. **Test installasjonen**
   ```bash
   composer serve
   # Gå til http://localhost:8000
   ```

## Konfigurasjon

### Database
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=jaktfeltcup
DB_USER=root
DB_PASSWORD=your_password
```

### E-post (PHPMailer)
```env
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@jaktfeltcup.no
MAIL_FROM_NAME="Jaktfeltcup"
```

### SMS (Twilio)
```env
TWILIO_SID=your-twilio-sid
TWILIO_TOKEN=your-twilio-token
TWILIO_PHONE=+1234567890
```

## Bruk

### Første gang
1. Gå til `/admin` for å opprette administrator-bruker
2. Konfigurer sesonger og kategorier
3. Opprett poengsystem
4. Inviter arrangører og deltakere

### Daglig bruk
1. **Arrangører** oppretter stevner og registrerer resultater
2. **Deltakere** melder seg på stevner
3. **Publikum** følger resultater og sammenlagt

## Offline-funksjonalitet

Systemet støtter offline-registrering av resultater:
- Service Worker cacher nødvendige data
- Resultater lagres lokalt når offline
- Automatisk synkronisering når tilkobling gjenopprettes

## Sikkerhet

- JWT-basert autentisering
- Rollbasert tilgangskontroll
- Passord-hashing med bcrypt
- SQL injection-beskyttelse med prepared statements
- XSS-beskyttelse med input-validering

## API

Systemet tilbyr REST API for:
- Mobile apper
- Integrasjon med andre systemer
- Offline-synkronisering

### Autentisering
```bash
# Logg inn
POST /auth/login
{
  "email": "user@example.com",
  "password": "password"
}

# Bruk token i Authorization header
Authorization: Bearer <token>
```

## Utvikling

### Lokal utvikling
```bash
# Start utviklingsserver
composer serve

# Kjøre tester
composer test

# Kodeanalyse
composer analyze
```

### Testing
```bash
# Kjøre alle tester
phpunit

# Kjøre spesifikke tester
phpunit tests/Unit/AuthServiceTest.php
```

## Lisens

Dette prosjektet er lisensiert under MIT-lisensen.

## Support

For spørsmål eller problemer, opprett en issue i GitHub-repositoriet.
