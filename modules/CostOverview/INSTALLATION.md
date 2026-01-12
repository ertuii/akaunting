# Installation Guide - Cost Overview Module

## Voraussetzungen / Prerequisites

- Akaunting 3.x oder höher / Akaunting 3.x or higher
- PHP 8.1 oder höher / PHP 8.1 or higher
- Laravel 10.x oder höher / Laravel 10.x or higher
- Aktivierte Mailer-Konfiguration in Akaunting / Active mailer configuration in Akaunting

## Installation

### Automatische Installation (empfohlen / recommended)

1. **Modul-Dateien kopieren / Copy module files:**
   ```bash
   cd /pfad/zu/akaunting
   # Stellen Sie sicher, dass das modules/CostOverview Verzeichnis existiert
   # Ensure the modules/CostOverview directory exists
   ```

2. **Module registrieren / Register module:**
   
   Das Modul wird automatisch von Akaunting erkannt, sobald es im `modules/` Verzeichnis liegt.
   The module is automatically detected by Akaunting once it's in the `modules/` directory.

3. **Cache leeren / Clear cache:**
   ```bash
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   php artisan config:clear
   ```

4. **Modul aktivieren / Activate module:**
   
   - Öffnen Sie die Akaunting Admin-Oberfläche
   - Navigieren Sie zu "Apps" oder "Module"
   - Suchen Sie "Cost Overview"
   - Klicken Sie auf "Aktivieren" oder "Enable"
   
   OR
   
   - Open the Akaunting admin interface
   - Navigate to "Apps" or "Modules"
   - Search for "Cost Overview"
   - Click "Activate" or "Enable"

### Manuelle Installation / Manual Installation

Wenn das Modul nicht automatisch erkannt wird:
If the module is not detected automatically:

1. **Autoload aktualisieren / Update autoload:**
   ```bash
   cd /pfad/zu/akaunting
   composer dump-autoload
   ```

2. **Module.json überprüfen / Check module.json:**
   
   Stellen Sie sicher, dass die Datei `modules/CostOverview/module.json` existiert und korrekt formatiert ist.
   Ensure that the file `modules/CostOverview/module.json` exists and is correctly formatted.

3. **Service Provider registrieren / Register service provider:**
   
   Der Service Provider sollte automatisch geladen werden. Falls nicht, prüfen Sie die Datei `modules/CostOverview/Providers/Main.php`.
   The service provider should be loaded automatically. If not, check the file `modules/CostOverview/Providers/Main.php`.

## Konfiguration / Configuration

### E-Mail-Einstellungen / Email Settings

Das Modul verwendet die Standard-E-Mail-Einstellungen von Akaunting. Stellen Sie sicher, dass diese korrekt konfiguriert sind:
The module uses Akaunting's standard email settings. Ensure they are configured correctly:

1. Gehen Sie zu **Einstellungen > E-Mail** / Go to **Settings > Email**
2. Konfigurieren Sie Ihren SMTP-Server / Configure your SMTP server
3. Testen Sie die E-Mail-Verbindung / Test the email connection

### Modul-Konfiguration / Module Configuration

Die Modul-Einstellungen können in der Datei `modules/CostOverview/Config/config.php` angepasst werden:
Module settings can be adjusted in the file `modules/CostOverview/Config/config.php`:

```php
return [
    // Anzahl der Monate für letzte Transaktionen
    // Number of months for recent transactions
    'transaction_months' => 3,
    
    // Rechnungsstatus für Kostenübersicht
    // Invoice statuses for cost overview
    'unpaid_statuses' => [
        'sent',
        'viewed',
        'partial',
    ],
    
    // Funktionen aktivieren/deaktivieren
    // Enable/disable features
    'features' => [
        'email' => true,
        'pdf' => true,
        'print' => true,
    ],
];
```

## Verwendung / Usage

### Zugriff auf das Modul / Access the Module

Nach erfolgreicher Installation finden Sie das Modul im Admin-Menü unter "Cost Overviews" / "Kostenübersichten".
After successful installation, find the module in the admin menu under "Cost Overviews" / "Kostenübersichten".

Alternativ können Sie direkt auf die URL zugreifen:
Alternatively, you can access it directly via URL:
```
https://ihre-akaunting-installation.com/cost-overviews
```

### Berechtigungen / Permissions

Das Modul verwendet standardmäßig die Admin-Middleware. Für feinere Berechtigungssteuerung können Sie die Datei `modules/CostOverview/Listeners/ShowInMenu.php` anpassen.
The module uses the admin middleware by default. For more fine-grained permission control, you can adjust the file `modules/CostOverview/Listeners/ShowInMenu.php`.

## Fehlerbehebung / Troubleshooting

### Modul wird nicht angezeigt / Module is not displayed

1. Cache leeren: / Clear cache:
   ```bash
   php artisan cache:clear
   php artisan route:clear
   php artisan config:clear
   ```

2. Überprüfen Sie die Logs: / Check the logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Autoload neu generieren: / Regenerate autoload:
   ```bash
   composer dump-autoload
   ```

### E-Mails werden nicht versendet / Emails are not sent

1. Überprüfen Sie die E-Mail-Konfiguration in Akaunting / Check email configuration in Akaunting
2. Überprüfen Sie den Queue-Status (falls Queues verwendet werden) / Check queue status (if using queues)
3. Prüfen Sie die Logs für Fehler / Check logs for errors

### PDF wird nicht generiert / PDF is not generated

1. Stellen Sie sicher, dass DomPDF installiert ist (bereits in Akaunting enthalten) / Ensure DomPDF is installed (already included in Akaunting)
2. Überprüfen Sie die Schreibrechte für das temp-Verzeichnis / Check write permissions for temp directory:
   ```bash
   chmod -R 755 storage/app/temp
   ```

### Übersetzungen fehlen / Translations are missing

1. Überprüfen Sie, ob die Sprachdateien vorhanden sind / Check if language files exist:
   ```
   modules/CostOverview/Resources/lang/de-DE/
   modules/CostOverview/Resources/lang/en-GB/
   ```

2. Cache leeren: / Clear cache:
   ```bash
   php artisan cache:clear
   ```

## Deinstallation / Uninstallation

Um das Modul zu deinstallieren / To uninstall the module:

1. Deaktivieren Sie das Modul in der Akaunting-Oberfläche / Deactivate the module in the Akaunting interface
2. Löschen Sie das Verzeichnis: / Delete the directory:
   ```bash
   rm -rf modules/CostOverview
   ```
3. Cache leeren: / Clear cache:
   ```bash
   php artisan cache:clear
   php artisan route:clear
   ```

## Support

Bei Problemen oder Fragen erstellen Sie bitte ein Issue im GitHub-Repository.
For problems or questions, please create an issue in the GitHub repository.
