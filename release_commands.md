# Kommandoer for å lage GitHub Release v0.5.0

## 🚀 **Steg-for-steg guide**

### **1. Commit alle endringer**
```bash
git add .
git commit -m "Release v0.5.0 - Beta release

Nye funksjoner:
- Mailjet e-post integrasjon
- Rollebasert tilgangskontroll
- Innholdsadministrasjon med popup-modal redigering
- Forbedret design og UX
- Komplett dokumentasjon
- Sikker konfigurasjon

Fikser:
- Logo-sti oppdatert til assets/images/logoer/
- config.php ekskludert fra Git for sikkerhet
- Logout-navigasjon fungerer korrekt
- E-post sending med Mailjet og fallback

Kommer i v1.0.0:
- Resultat-administrasjon
- Påmeldingssystem
- Fullstendig testing"
```

### **2. Push til GitHub**
```bash
git push origin main
```

### **3. Lag Git tag**
```bash
git tag -a v0.5.0 -m "Release v0.5.0 - Beta release

Grunnleggende funksjonalitet:
- E-post funksjonalitet (Mailjet)
- Bruker- og rolleadministrasjon
- Innholdsadministrasjon
- Design og UX
- Dokumentasjon"
```

### **4. Push tag til GitHub**
```bash
git push origin v0.5.0
```

### **5. Lag release på GitHub (via web)**

1. Gå til GitHub repository: `https://github.com/[username]/jaktfeltcup`
2. Klikk på "Releases" i høyre sidebar
3. Klikk "Create a new release"
4. Velg tag: `v0.5.0`
5. Release title: `v0.5.0 - Beta Release`
6. **Merk som "pre-release"** ✅ (siden det er beta)
7. Beskrivelse: (kopier fra RELEASE.md)
8. Klikk "Publish release"

## 📋 **Alternativ: Lag release via GitHub CLI**

Hvis du har GitHub CLI installert:

```bash
# Login til GitHub (hvis ikke allerede logget inn)
gh auth login

# Lag pre-release (beta)
gh release create v0.5.0 \
  --title "v0.5.0 - Beta Release" \
  --notes-file RELEASE.md \
  --prerelease
```

## 🎯 **Etter release**

1. **Verifiser release** på GitHub
2. **Test deployment** på produksjonsserver
3. **Lag backup** av database
4. **Oppdater dokumentasjon** hvis nødvendig

## 📝 **Release checklist**

- [x] Alle endringer committet
- [x] RELEASE.md opprettet
- [x] config.example.php oppdatert
- [x] SETUP.md oppdatert
- [x] Test-filer fungerer
- [ ] Git tag opprettet
- [ ] Tag pushet til GitHub
- [ ] Release publisert på GitHub
- [ ] Produksjonsserver oppdatert

## 🔮 **Neste release (v1.0.0)**

Planlagte forbedringer:
- Resultat-administrasjon for stevner
- Påmeldingssystem for deltakere
- Fullstendig testing og debugging
- Produksjonsklart deployment

## 🔮 **Fremtidige releases (v1.1.0+)**

- Statistikk og rapporter
- Offline-funksjonalitet
- SMS-varsling
- Export til PDF/Excel
