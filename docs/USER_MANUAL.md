# Jaktfeltcup - Brukerdokumentasjon

## Innholdsfortegnelse
1. [Introduksjon](#introduksjon)
2. [Første gang](#første-gang)
3. [Brukerroller](#brukerroller)
4. [Navigasjon](#navigasjon)
5. [Arrangør-funksjoner](#arrangør-funksjoner)
6. [Sponsor-funksjoner](#sponsor-funksjoner)
7. [Deltaker-funksjoner](#deltaker-funksjoner)
8. [Publikum-funksjoner](#publikum-funksjoner)
9. [Admin-funksjoner](#admin-funksjoner)
10. [Innholdsredigering](#innholdsredigering)
11. [Feilsøking](#feilsøking)
12. [Kontakt](#kontakt)

---

## Introduksjon

Jaktfeltcup er en webapplikasjon for administrasjon av skytekonkurranser. Systemet støtter flere brukerroller og tilbyr funksjoner for arrangører, sponsorer, deltakere og publikum.

### Hovedfunksjoner
- **Arrangører**: Opprett og administrer stevner
- **Sponsorer**: Få synlighet og markedsføring
- **Deltakere**: Meld deg på stevner og se resultater
- **Publikum**: Følg med på resultater og nyheter
- **Admin**: Administrer systemet og brukere

---

## Første gang

### Tilgang til systemet
1. Gå til `http://localhost/jaktfeltcup/` (lokalt) eller din domene
2. Velg din rolle fra hovedmenyen
3. Logg inn eller registrer deg som ny bruker

### Oppsett av første admin
Hvis du er systemadministrator og trenger å sette opp første admin-bruker:

```bash
# Metode 1: PHP script
php scripts/setup/create_single_user.php

# Metode 2: SQL script
# Kjør database/test_users.sql i MySQL

# Metode 3: Web-basert
# Gå til /admin/setup-admin
```

---

## Brukerroller

### 1. Admin
**Tilgang**: Full systemtilgang
**Funksjoner**:
- Administrer alle brukere og roller
- Tilgang til alle admin-funksjoner
- Kan redigere alt innhold

### 2. Database Manager
**Tilgang**: Database-administrasjon
**Funksjoner**:
- Administrer database
- Importer/eksporter data
- Database-vedlikehold

### 3. Content Manager
**Tilgang**: Innholdsredigering
**Funksjoner**:
- Rediger tekst på alle sider
- Administrer nyheter
- Administrer sponsorer

### 4. Role Manager
**Tilgang**: Bruker- og rollestyring
**Funksjoner**:
- Administrer brukere
- Tildele roller
- Brukeradministrasjon

### 5. Deltaker
**Tilgang**: Deltaker-funksjoner
**Funksjoner**:
- Meld deg på stevner
- Se dine resultater
- Rediger profil

---

## Navigasjon

### Hovedmeny
- **Hjem**: Landingsside med oversikt
- **Arrangør**: Informasjon for arrangører
- **Sponsor**: Informasjon for sponsorer
- **Deltaker**: Deltaker-funksjoner
- **Publikum**: Offentlig informasjon

### Betinget navigasjon
- **Resultater/Sammenlagt**: Kun synlig i resultater-seksjonen
- **Logg inn/Registrer**: Kun synlig i deltaker-seksjonen
- **Admin**: Kun tilgjengelig via adresselinje for admin-brukere

---

## Arrangør-funksjoner

### Hovedside
- **URL**: `/arrangor`
- **Innhold**: Informasjon om å bli arrangør
- **Redigering**: Content Manager kan redigere tekst

### Bli arrangør
- **URL**: `/arrangor/bli-arrangor`
- **Funksjon**: Informasjon om prosessen

### Kontakt
- **URL**: `/arrangor/kontakt`
- **Funksjon**: Kontaktinformasjon

### Admin-funksjoner
- **Opprett stevner**
- **Administrer deltakere**
- **Legg inn resultater**
- **Kommuniser med deltakere**

---

## Sponsor-funksjoner

### Hovedside
- **URL**: `/sponsor`
- **Innhold**: Informasjon om sponsormuligheter
- **Redigering**: Content Manager kan redigere tekst

### Sponsor-pakker
- **URL**: `/sponsor/pakker`
- **Funksjon**: Oversikt over tilgjengelige pakker

### Sponsor-presentasjon
- **URL**: `/sponsor/presentasjon`
- **Funksjon**: Viser alle aktive sponsorer

### Kontakt
- **URL**: `/sponsor/kontakt`
- **Funksjon**: Kontaktinformasjon for sponsorer

---

## Deltaker-funksjoner

### Hovedside
- **URL**: `/deltaker`
- **Innhold**: Informasjon for deltakere
- **Redigering**: Content Manager kan redigere tekst

### Meld deg på
- **URL**: `/deltaker/meld-deg-pa`
- **Funksjon**: Påmelding til stevner

### Regler
- **URL**: `/deltaker/regler`
- **Funksjon**: Regler og retningslinjer

### Dashboard
- **URL**: `/participant/dashboard`
- **Funksjoner**:
  - Se dine påmeldinger
  - Se dine resultater
  - Rediger profil
  - Hurtighandlinger

---

## Publikum-funksjoner

### Hovedside
- **URL**: `/publikum`
- **Innhold**: Offentlig informasjon
- **Redigering**: Content Manager kan redigere tekst

### Kalender
- **URL**: `/publikum/kalender`
- **Funksjon**: Stevne-kalender

### Nyheter
- **URL**: `/publikum/nyheter`
- **Funksjon**: Siste nytt

### Resultater
- **URL**: `/results`
- **Funksjon**: Se resultater fra stevner

### Sammenlagt
- **URL**: `/standings`
- **Funksjon**: Sammenlagtstilling

---

## Admin-funksjoner

### Admin Dashboard
- **URL**: `/admin`
- **Tilgang**: Admin-roller
- **Funksjoner**:
  - Database-administrasjon
  - Innholdsredigering
  - Bruker- og rollestyring

### Database-administrasjon
- **URL**: `/admin/database`
- **Tilgang**: Database Manager
- **Funksjoner**:
  - Database-oversikt
  - Import/eksport
  - Vedlikehold

### Innholdsredigering
- **URL**: `/admin/content`
- **Tilgang**: Content Manager
- **Funksjoner**:
  - Rediger nyheter
  - Administrer sponsorer
  - Tekstinnhold

### Bruker- og rollestyring
- **URL**: `/admin/roles`
- **Tilgang**: Role Manager
- **Funksjoner**:
  - Administrer brukere
  - Tildele roller
  - Brukeradministrasjon

---

## Innholdsredigering

### Inline-redigering
Content Manager kan redigere tekst direkte på sidene:

1. **Logg inn som Content Manager**
2. **Gå til en side** (f.eks. `/arrangor`)
3. **Hover over tekst** → Blå kantlinje vises
4. **Klikk "Rediger"** → Popup-modal åpnes
5. **Rediger tekst** → Klikk "Lagre"
6. **Endringer lagres** automatisk

### Admin-redigering
- **Nyheter**: `/admin/content` → Nyheter
- **Sponsorer**: `/admin/content` → Sponsorer
- **Tekstinnhold**: `/admin/content/text`

### Redigerbare elementer
- Hero-seksjoner (tittel og innhold)
- Seksjonstitler
- Beskrivelser
- Card-innhold
- Call-to-Action seksjoner

---

## Feilsøking

### Innloggingsproblemer
1. **Sjekk brukernavn/e-post**
2. **Sjekk passord**
3. **Sjekk at bruker er aktiv** (`is_active = 1`)
4. **Kjør debug-script**: `php test_login_debug.php`

### Database-problemer
1. **Sjekk database-tilkobling** i `config/config.php`
2. **Kjør database-setup**: `database/schema.sql`
3. **Sjekk tabellstruktur**

### URL-problemer
1. **Sjekk `.htaccess`** fil
2. **Sjekk `base_url`** i config
3. **Sjekk server-konfigurasjon**

### Bilde-problemer
1. **Sjekk filstier** i `assets/images/`
2. **Sjekk tillatelser** på filer
3. **Sjekk ad-blocker** (kan blokkere bilder)

---

## Kontakt

### Support
- **E-post**: support@jaktfeltcup.no
- **Telefon**: +47 XXX XX XXX

### Utvikler
- **GitHub**: [Repository URL]
- **Dokumentasjon**: [Dokumentasjon URL]

### Systemadministrator
- **E-post**: admin@jaktfeltcup.no
- **Telefon**: +47 XXX XX XXX

---

## Vedlegg

### A. Bruker-scripts
```bash
# Opprett enkelt bruker
php scripts/setup/create_single_user.php

# Opprett flere brukere
php scripts/setup/create_users_php.php

# Fra CSV-fil
php scripts/setup/create_users_from_csv.php users.csv

# Fra JSON-fil
php scripts/setup/create_users_from_json.php users.json
```

### B. Database-scripts
```sql
-- Opprett test-brukere
SOURCE database/test_users.sql;

-- Opprett alle brukere
SOURCE database/create_users.sql;
```

### C. Konfigurasjon
```php
// config/config.php
$app_config = [
    'base_url' => '/jaktfeltcup/',  // Lokalt
    'base_url' => '/',              // Produksjon
];
```

---

*Dokumentasjon oppdatert: [Dato]*
*Versjon: 1.0*
