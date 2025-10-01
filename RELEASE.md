# Release Notes - Nasjonal 15m Jaktfeltcup v0.5.0

## 🚧 **Beta release - Grunnleggende funksjonalitet**

Dato: 1. oktober 2025

### **✨ Nye funksjoner**

#### **📧 E-post funksjonalitet**
- ✅ Mailjet integrasjon for profesjonell e-post sending
- ✅ E-post verifisering ved registrering
- ✅ Passord tilbakestilling via e-post
- ✅ Fallback til PHP mail() hvis Mailjet feiler
- ✅ 95%+ leveringsrate med Mailjet

#### **👥 Brukeradministrasjon**
- ✅ Rollebasert tilgangskontroll (RBAC)
- ✅ Tre admin-roller: `databasemanager`, `contentmanager`, `rolemanager`
- ✅ Brukerprofiler med personlig informasjon
- ✅ Sikker passord-håndtering med bcrypt
- ✅ Brukerregistrering og innlogging

#### **📝 Innholdsadministrasjon**
- ✅ Popup-modal redigering for innhold
- ✅ Redigerbare seksjoner på alle offentlige sider
- ✅ Nyhets-administrasjon
- ✅ Sponsor-administrasjon med logo-opplasting
- ✅ Database-lagret innhold

#### **🎨 Design og UX**
- ✅ Mobile-first responsive design
- ✅ Bootstrap 5 for moderne UI
- ✅ Konsistent hero-seksjon på alle sider
- ✅ Gradient bakgrunn som matcher logo
- ✅ Gjennomsiktig bakgrunnslogo på alle sider
- ✅ Forbedret navigasjon med betinget visning

#### **📄 Sider og seksjoner**
- ✅ **Landingsside** - Hovedside med CTA-knapper
- ✅ **Arrangør-seksjon** - Informasjon for arrangører
- ✅ **Sponsor-seksjon** - Sponsorpakker og presentasjon
- ✅ **Deltaker-seksjon** - Påmelding og informasjon
- ✅ **Publikum-seksjon** - Resultater og kalender
- ✅ **Om oss** - Presentasjon av hovedkomite og cup-informasjon
- ✅ **Dokumentasjon** - Brukermanual integrert i appen

#### **🔒 Sikkerhet**
- ✅ Session-basert autentisering
- ✅ Password hashing med bcrypt
- ✅ CSRF-beskyttelse
- ✅ SQL injection-beskyttelse med PDO
- ✅ Sikker konfigurasjon (config.php ekskludert fra Git)
- ✅ Rollebasert tilgangskontroll

#### **🗄️ Database**
- ✅ MySQL database med PDO
- ✅ Tabell-prefiks (`jaktfelt_`) for navnerom
- ✅ Migrasjons-scripts for database-oppdateringer
- ✅ Test-data scripts for utvikling
- ✅ Database administrasjonspanel

### **🔧 Teknisk stack**

- **Backend**: PHP 8.0+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **E-post**: Mailjet API
- **Autentisering**: Session-based
- **Version control**: Git / GitHub

### **📦 Installasjon**

Se `SETUP.md` for detaljert installasjonsveiledning.

### **🚀 Deployment**

Applikasjonen støtter deployment via:
- Git pull på server
- FTP upload
- GitHub Actions (kan konfigureres)

### **📚 Dokumentasjon**

- `SETUP.md` - Installasjonsveiledning
- `MAILJET_SETUP.md` - E-post konfigurasjon
- `/dokumentasjon` - Brukermanual (tilgjengelig i appen)

### **🐛 Kjente problemer**

- Logo må flyttes fra `bilder/` til `assets/images/logoer/` for deployment
- Resultat-administrasjon er ikke implementert enda
- Påmeldingssystem er ikke implementert enda
- Offline-funksjonalitet mangler

### **🔮 Kommende i v1.0.0**

- [ ] Resultat-administrasjon for stevner
- [ ] Påmeldingssystem for deltakere
- [ ] Fullstendig testing og debugging
- [ ] Produksjonsklart deployment

### **🔮 Fremtidige forbedringer (v1.1.0+)**

- [ ] Offline-funksjonalitet for arrangører (Service Worker)
- [ ] Statistikk og rapporter
- [ ] Export til PDF/Excel
- [ ] SMS-varsling
- [ ] Mobilapp

### **👥 Bidragsytere**

Utviklet for Nasjonal 15m Jaktfeltcup

### **📄 Lisens**

Proprietær - Alle rettigheter forbeholdt

---

## 🎯 **Oppgradering fra tidligere versjoner**

Dette er første release, ingen oppgradering nødvendig.

## 💾 **Database endringer**

Kjør følgende scripts for ny installasjon:
```bash
php scripts/setup/create_database.php
php scripts/setup/setup_roles.php
```

## ⚙️ **Konfigurasjon**

1. Kopier `config/config.example.php` til `config/config.php`
2. Oppdater database-opplysninger
3. Legg til Mailjet API-nøkler (valgfritt)
4. Test installasjon med test-scripts

---

**For support og spørsmål, se dokumentasjonen i appen.**
