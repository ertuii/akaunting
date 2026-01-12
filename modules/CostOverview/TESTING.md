# Testing Guide - Cost Overview Module

## Manuelle Tests / Manual Testing

### 1. Modul-Installation testen / Test Module Installation

- [ ] Modul erscheint im Admin-Menü unter "Cost Overviews"
- [ ] Module appears in admin menu under "Cost Overviews"

### 2. Listenansicht testen / Test List View

**URL:** `/cost-overviews`

- [ ] Liste zeigt alle Kunden an / List displays all customers
- [ ] Offene Beträge werden korrekt angezeigt / Outstanding amounts are displayed correctly
- [ ] E-Mail-Adressen werden angezeigt / Email addresses are shown
- [ ] Aktionen-Dropdown ist verfügbar / Actions dropdown is available

### 3. Detailansicht testen / Test Detail View

**URL:** `/cost-overviews/{customer_id}`

- [ ] Kundeninformationen werden angezeigt / Customer information is displayed
- [ ] Unbezahlte Rechnungen werden aufgelistet / Unpaid invoices are listed
- [ ] Letzte Transaktionen (3 Monate) werden angezeigt / Recent transactions (3 months) are shown
- [ ] Gesamtsummen werden korrekt berechnet / Totals are calculated correctly
- [ ] Buttons für E-Mail, PDF und Druck sind vorhanden / Buttons for email, PDF, and print are present

### 4. PDF-Export testen / Test PDF Export

**URL:** `/cost-overviews/{customer_id}/pdf`

- [ ] PDF wird generiert / PDF is generated
- [ ] PDF enthält alle Kundeninformationen / PDF contains all customer information
- [ ] Unbezahlte Rechnungen sind im PDF enthalten / Unpaid invoices are in the PDF
- [ ] Transaktionen sind im PDF enthalten / Transactions are in the PDF
- [ ] Formatierung ist korrekt / Formatting is correct
- [ ] PDF wird heruntergeladen / PDF is downloaded

### 5. E-Mail-Versand testen / Test Email Sending

**URL:** `/cost-overviews/{customer_id}/email`

**Voraussetzungen / Prerequisites:**
- Kunde muss eine E-Mail-Adresse haben / Customer must have an email address
- E-Mail-Konfiguration muss in Akaunting eingerichtet sein / Email configuration must be set up in Akaunting

- [ ] E-Mail wird versendet / Email is sent
- [ ] E-Mail enthält PDF-Anhang / Email contains PDF attachment
- [ ] E-Mail-Betreff ist korrekt / Email subject is correct
- [ ] E-Mail-Inhalt ist korrekt / Email content is correct
- [ ] Erfolgsmeldung wird angezeigt / Success message is displayed

### 6. Druckansicht testen / Test Print View

**URL:** `/cost-overviews/{customer_id}/print`

- [ ] Druckansicht öffnet sich in neuem Tab / Print view opens in new tab
- [ ] Layout ist druckoptimiert / Layout is print-optimized
- [ ] Alle Informationen sind vorhanden / All information is present
- [ ] Druck-Button funktioniert / Print button works

### 7. Übersetzungen testen / Test Translations

**Deutsch (de-DE):**
- [ ] Alle Texte werden auf Deutsch angezeigt / All texts are displayed in German
- [ ] E-Mail-Betreff ist auf Deutsch / Email subject is in German
- [ ] PDF-Inhalte sind auf Deutsch / PDF contents are in German

**Englisch (en-GB):**
- [ ] Alle Texte werden auf Englisch angezeigt / All texts are displayed in English
- [ ] E-Mail-Betreff ist auf Englisch / Email subject is in English
- [ ] PDF-Inhalte sind auf Englisch / PDF contents are in English

## Test-Szenarien / Test Scenarios

### Szenario 1: Kunde mit unbezahlten Rechnungen
**Scenario 1: Customer with unpaid invoices**

1. Erstellen Sie einen Testkunden / Create a test customer
2. Erstellen Sie 2-3 unbezahlte Rechnungen / Create 2-3 unpaid invoices
3. Öffnen Sie die Kostenübersicht / Open the cost overview
4. Überprüfen Sie, ob alle Rechnungen angezeigt werden / Verify all invoices are displayed
5. Exportieren Sie das PDF / Export the PDF
6. Senden Sie die E-Mail / Send the email

**Erwartetes Ergebnis / Expected Result:**
- Alle unbezahlten Rechnungen werden angezeigt / All unpaid invoices are displayed
- Gesamtsumme ist korrekt / Total sum is correct
- PDF und E-Mail enthalten alle Daten / PDF and email contain all data

### Szenario 2: Kunde ohne unbezahlte Rechnungen
**Scenario 2: Customer without unpaid invoices**

1. Erstellen Sie einen Testkunden / Create a test customer
2. Erstellen Sie nur bezahlte Rechnungen / Create only paid invoices
3. Öffnen Sie die Kostenübersicht / Open the cost overview

**Erwartetes Ergebnis / Expected Result:**
- Meldung "Keine unbezahlten Rechnungen" wird angezeigt / Message "No unpaid invoices" is displayed
- Gesamtsumme ist 0 / Total sum is 0

### Szenario 3: Kunde mit Transaktionen
**Scenario 3: Customer with transactions**

1. Erstellen Sie einen Testkunden / Create a test customer
2. Erstellen Sie mehrere Zahlungseingänge / Create multiple income transactions
3. Öffnen Sie die Kostenübersicht / Open the cost overview

**Erwartetes Ergebnis / Expected Result:**
- Transaktionen der letzten 3 Monate werden angezeigt / Transactions of the last 3 months are displayed
- Gesamtsumme der Zahlungen ist korrekt / Total sum of payments is correct

### Szenario 4: Kunde ohne E-Mail-Adresse
**Scenario 4: Customer without email address**

1. Erstellen Sie einen Testkunden ohne E-Mail / Create a test customer without email
2. Versuchen Sie, eine E-Mail zu senden / Try to send an email

**Erwartetes Ergebnis / Expected Result:**
- E-Mail-Button ist nicht verfügbar / Email button is not available
- Oder: Fehlermeldung wird angezeigt / Or: Error message is displayed

## Fehlerbehandlung testen / Test Error Handling

### 1. Ungültige Kunden-ID
**Invalid customer ID**

**URL:** `/cost-overviews/99999`

**Erwartetes Ergebnis / Expected Result:**
- 404-Fehlerseite oder Umleitung zur Liste / 404 error page or redirect to list

### 2. E-Mail ohne Konfiguration
**Email without configuration**

Deaktivieren Sie die E-Mail-Konfiguration in Akaunting und versuchen Sie, eine E-Mail zu senden.
Disable email configuration in Akaunting and try to send an email.

**Erwartetes Ergebnis / Expected Result:**
- Aussagekräftige Fehlermeldung / Meaningful error message
- Keine Anwendungsabstürze / No application crashes

## Performance-Tests / Performance Tests

### Große Datenmengen / Large Data Sets

1. Erstellen Sie einen Kunden mit 50+ Rechnungen / Create a customer with 50+ invoices
2. Erstellen Sie 100+ Transaktionen / Create 100+ transactions
3. Öffnen Sie die Kostenübersicht / Open the cost overview

**Zu überprüfen / To verify:**
- [ ] Seite lädt in angemessener Zeit (< 5 Sekunden) / Page loads in reasonable time (< 5 seconds)
- [ ] PDF-Generierung funktioniert / PDF generation works
- [ ] Keine Memory-Fehler / No memory errors

## Browser-Kompatibilität / Browser Compatibility

Testen Sie die Funktionalität in folgenden Browsern:
Test functionality in the following browsers:

- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari
- [ ] Edge

## Mobile Responsiveness

Testen Sie die Ansichten auf mobilen Geräten:
Test views on mobile devices:

- [ ] Listenansicht ist responsiv / List view is responsive
- [ ] Detailansicht ist responsiv / Detail view is responsive
- [ ] Buttons sind gut erreichbar / Buttons are easily accessible

## Checkliste vor Release / Pre-Release Checklist

- [ ] Alle manuellen Tests bestanden / All manual tests passed
- [ ] Keine Fehler in den Logs / No errors in logs
- [ ] Übersetzungen vollständig / Translations complete
- [ ] README dokumentiert alle Features / README documents all features
- [ ] Installation Guide ist vollständig / Installation guide is complete
- [ ] Code folgt Akaunting-Konventionen / Code follows Akaunting conventions
- [ ] Keine Sicherheitslücken bekannt / No known security vulnerabilities
