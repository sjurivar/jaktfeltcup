#!/bin/bash
# Commit script for v0.5.0 release

# Add all changes
git add .

# Commit with detailed message
git commit -m "Release v0.5.0 - Beta release

Nye funksjoner og endringer:
- Mailjet e-post integrasjon med fallback til PHP mail()
- Rollebasert tilgangskontroll (databasemanager, contentmanager, rolemanager)
- Innholdsadministrasjon med popup-modal redigering
- Forbedret navigasjon (Om i header og footer)
- Sponsor-side oppdatert (kontakt hovedkomité i stedet for pakker)
- Logo-sti oppdatert til assets/images/logoer/ med fallback
- config.php ekskludert fra Git for sikkerhet
- Logout-navigasjon fungerer korrekt
- Email service med Mailjet og fallback
- Release notes og dokumentasjon

Fikser:
- Logo-sti fikset i ImageHelper
- config.php ekskludert fra version control
- Navigasjon oppdatert (Om i header og footer)
- Sponsor-pakker erstattet med kontaktinfo
- Footer oppdatert med lenker til Om, Dokumentasjon, Teknisk info

Dokumentasjon:
- RELEASE.md for v0.5.0
- release_commands.md med Git-kommandoer
- SETUP.md for installasjon
- MAILJET_SETUP.md for e-post konfigurasjon
- config.example.php som mal

Kommer i v1.0.0:
- Resultat-administrasjon
- Påmeldingssystem
- Fullstendig testing"

# Show what was committed
git log -1 --stat

echo ""
echo "✅ Commit created successfully!"
echo ""
echo "Next steps:"
echo "1. Push to GitHub: git push origin main"
echo "2. Create tag: git tag -a v0.5.0 -m 'Release v0.5.0 - Beta release'"
echo "3. Push tag: git push origin v0.5.0"
echo "4. Create GitHub release (see release_commands.md)"

