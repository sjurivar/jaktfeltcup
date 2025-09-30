# Mailjet Setup Guide

## 🚀 **Hva er Mailjet?**

Mailjet er en profesjonell e-post service som gir:
- **Høy leveringsrate** (bedre enn vanlig SMTP)
- **Riktig API** for e-post sending
- **Analytics og tracking**
- **Bedre spam-filtering**
- **Gratis tier** (6,000 e-poster/måned)

## 📋 **Steg 1: Opprett Mailjet konto**

1. Gå til [mailjet.com](https://www.mailjet.com)
2. Klikk "Sign Up" og opprett konto
3. Verifiser e-post adressen din

## 🔑 **Steg 2: Få API nøkler**

1. Logg inn på Mailjet dashboard
2. Gå til **Account Settings** → **API Key Management**
3. Kopier **API Key** og **Secret Key**

## ⚙️ **Steg 3: Oppdater config.php**

```php
// Mailjet configuration
$mailjet_config = [
    'api_key' => 'din_api_key_her',        // Fra Mailjet dashboard
    'secret_key' => 'din_secret_key_her',  // Fra Mailjet dashboard
    'from_email' => 'noreply@jaktfeltcup.no',
    'from_name' => 'Jaktfeltcup'
];
```

## 🌐 **Steg 4: Verifiser domene**

1. Gå til **Senders & Domains** i Mailjet dashboard
2. Legg til `jaktfeltcup.no` som sender domene
3. Følg DNS-verifiseringsprosessen

## 🧪 **Steg 5: Test sending**

```bash
# Test på serveren
php test_mailjet.php
# eller
php test_mailjet_simple.php
```

## 📊 **Fordeler med Mailjet vs SMTP**

| Feature | SMTP | Mailjet |
|---------|------|---------|
| Leveringsrate | 70-80% | 95%+ |
| Analytics | Nei | Ja |
| Tracking | Nei | Ja |
| Spam-filtering | Grunnleggende | Avansert |
| API | Nei | Ja |
| Gratis tier | Begrenset | 6,000/måned |

## 🔧 **Bruk i applikasjonen**

```php
// I din kode
$emailService = new EmailServiceMailjet($app_config, $db, $mailjet_config);

$result = $emailService->sendEmail(
    'user@example.com',
    'Velkommen til Jaktfeltcup',
    '<h1>Velkommen!</h1><p>Takk for registrering.</p>'
);
```

## 🚨 **Troubleshooting**

### **API nøkler ikke fungerer:**
- Sjekk at nøklene er kopiert riktig
- Sjekk at kontoen er aktivert
- Sjekk at domene er verifisert

### **E-poster ikke leveres:**
- Sjekk spam-mappe
- Sjekk at fra-adresse er verifisert
- Sjekk Mailjet dashboard for feilmeldinger

### **Rate limiting:**
- Gratis tier: 200 e-poster/dag
- Oppgrader til betalt plan for mer

## 📈 **Analytics og tracking**

Mailjet gir deg:
- **Åpningsrate** - hvor mange som åpner e-postene
- **Klikkrate** - hvor mange som klikker på lenker
- **Bounce rate** - hvor mange som ikke leveres
- **Unsubscribe rate** - hvor mange som melder seg av

## 🎯 **Neste steg**

1. **Opprett Mailjet konto**
2. **Få API nøkler**
3. **Oppdater config.php**
4. **Test sending**
5. **Verifiser domene**
6. **Deploy til produksjon**

---

**Tips:** Start med gratis tier for testing, oppgrader når du trenger mer volum.
