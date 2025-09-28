# Jaktfeltcup - Restrukturering TODO

## 🎯 **Mål: Markedsføring og Rekruttering**

### **Fase 1: Landing Page (Hovedside)**
- [ ] Opprett `views/public/landing.php` med hero section
- [ ] Legg til 3 hoved-CTA knapper: "Bli Arrangør", "Bli Sponsor", "Meld Deg På"
- [ ] Vis aktuelle stevner (kommende)
- [ ] Legg til siste nytt/nyheter
- [ ] Oppdater routing i `index.php`

### **Fase 2: Arrangør-seksjon**
- [ ] Opprett `views/public/arrangor/index.php`
- [ ] Opprett `views/public/arrangor/bli-arrangor.php`
- [ ] Opprett `views/public/arrangor/kontakt.php`
- [ ] Legg til routing for `/arrangor`
- [ ] Implementer kontakt-skjema for arrangører

### **Fase 3: Sponsor-seksjon**
- [ ] Opprett `views/public/sponsor/index.php`
- [ ] Opprett `views/public/sponsor/pakker.php`
- [ ] Opprett `views/public/sponsor/kontakt.php`
- [ ] Legg til routing for `/sponsor`
- [ ] Implementer sponsor-pakker (Bronze/Silver/Gold)

### **Fase 4: Deltaker-seksjon**
- [ ] Opprett `views/public/deltaker/index.php`
- [ ] Opprett `views/public/deltaker/meld-deg-pa.php`
- [ ] Opprett `views/public/deltaker/regler.php`
- [ ] Legg til routing for `/deltaker`
- [ ] Implementer påmelding til stevner

### **Fase 5: Publikum-seksjon**
- [ ] Opprett `views/public/publikum/index.php`
- [ ] Opprett `views/public/publikum/kalender.php`
- [ ] Opprett `views/public/publikum/nyheter.php`
- [ ] Legg til routing for `/publikum`
- [ ] Implementer stevne-kalender

### **Fase 6: Navigasjon og UI**
- [ ] Oppdater `views/layouts/header.php` med nye seksjoner
- [ ] Legg til dropdown-meny for hovedkategorier
- [ ] Implementer responsive design
- [ ] Legg til ikoner og visuell identitet

### **Fase 7: Backend og Funksjonalitet**
- [ ] Implementer kontakt-skjemaer (arrangør/sponsor)
- [ ] Legg til e-post notifikasjoner
- [ ] Implementer påmelding til stevner
- [ ] Legg til admin-funksjonalitet for nye seksjoner

### **Fase 8: Testing og Optimalisering**
- [ ] Test alle nye seksjoner
- [ ] Sjekk responsivt design
- [ ] Test kontakt-skjemaer
- [ ] Optimaliser for SEO
- [ ] Test på ulike enheter

## **Prioritering:**
1. **Landing page** (høyest prioritet)
2. **Arrangør-seksjon** (rekruttering)
3. **Sponsor-seksjon** (rekruttering)
4. **Deltaker-seksjon** (rekruttering)
5. **Publikum-seksjon** (engasjement)

## **Tekniske Detaljer:**
- Behold eksisterende funksjonalitet
- Legg til nye ruter i `index.php`
- Implementer responsive design
- Fokus på brukeropplevelse
- Markedsføring og rekruttering som hovedmål
