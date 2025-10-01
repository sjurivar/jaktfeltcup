@echo off
REM Commit script for v0.5.0 release (Windows)

echo Adding all changes...
git add .

echo.
echo Creating commit...
git commit -m "Release v0.5.0 - Beta release" -m "Nye funksjoner og endringer:" -m "- Mailjet e-post integrasjon med fallback til PHP mail()" -m "- Rollebasert tilgangskontroll (databasemanager, contentmanager, rolemanager)" -m "- Innholdsadministrasjon med popup-modal redigering" -m "- Forbedret navigasjon (Om i header og footer)" -m "- Sponsor-side oppdatert (kontakt hovedkomite i stedet for pakker)" -m "- Logo-sti oppdatert til assets/images/logoer/ med fallback" -m "- config.php ekskludert fra Git for sikkerhet" -m "- Logout-navigasjon fungerer korrekt" -m "- Email service med Mailjet og fallback" -m "- Release notes og dokumentasjon" -m "" -m "Fikser:" -m "- Logo-sti fikset i ImageHelper" -m "- config.php ekskludert fra version control" -m "- Navigasjon oppdatert (Om i header og footer)" -m "- Sponsor-pakker erstattet med kontaktinfo" -m "- Footer oppdatert med lenker til Om, Dokumentasjon, Teknisk info" -m "" -m "Dokumentasjon:" -m "- RELEASE.md for v0.5.0" -m "- release_commands.md med Git-kommandoer" -m "- SETUP.md for installasjon" -m "- MAILJET_SETUP.md for e-post konfigurasjon" -m "- config.example.php som mal" -m "" -m "Kommer i v1.0.0:" -m "- Resultat-administrasjon" -m "- Pameldingssystem" -m "- Fullstendig testing"

echo.
echo Commit created!
echo.

echo Last commit:
git log -1 --oneline

echo.
echo ================================
echo   Commit created successfully!
echo ================================
echo.
echo Next steps:
echo 1. Push to GitHub: git push origin main
echo 2. Create tag: git tag -a v0.5.0 -m "Release v0.5.0 - Beta release"
echo 3. Push tag: git push origin v0.5.0
echo 4. Create GitHub release (see release_commands.md)
echo.

pause

