# Cost Overview Module für Akaunting

Ein Modul für Akaunting, das es ermöglicht, Kunden eine Übersicht ihrer aktuellen Kosten zu senden - per E-Mail oder als PDF-Export.

## Features

- **Kundenübersicht**: Liste aller Kunden mit offenen Beträgen
- **Detaillierte Kostenübersicht**: Zeigt unbezahlte Rechnungen und letzte Transaktionen (3 Monate)
- **E-Mail-Versand**: Versenden Sie die Kostenübersicht direkt per E-Mail an den Kunden
- **PDF-Export**: Exportieren Sie die Übersicht als PDF-Datei
- **Druck-Ansicht**: Drucken Sie die Kostenübersicht direkt aus dem Browser
- **Mehrsprachig**: Unterstützt Deutsch (de-DE) und Englisch (en-GB)

## Installation

1. Kopieren Sie den Ordner `CostOverview` in das Verzeichnis `/modules/` Ihrer Akaunting-Installation
2. Aktivieren Sie das Modul in der Akaunting-Admin-Oberfläche unter "Apps"
3. Das Modul ist nun unter der URL `/cost-overviews` verfügbar

## Verwendung

### Kostenübersicht anzeigen

1. Navigieren Sie zu "Cost Overviews" im Admin-Menü
2. Wählen Sie einen Kunden aus der Liste aus
3. Die detaillierte Kostenübersicht wird angezeigt

### E-Mail versenden

1. Öffnen Sie die Kostenübersicht eines Kunden
2. Klicken Sie auf "E-Mail senden"
3. Die Kostenübersicht wird als PDF an die E-Mail-Adresse des Kunden gesendet

### PDF exportieren

1. Öffnen Sie die Kostenübersicht eines Kunden
2. Klicken Sie auf "PDF herunterladen"
3. Das PDF wird automatisch heruntergeladen

### Drucken

1. Öffnen Sie die Kostenübersicht eines Kunden
2. Klicken Sie auf "Drucken"
3. Eine druckoptimierte Ansicht öffnet sich in einem neuen Tab

## Inhalt der Kostenübersicht

Die Kostenübersicht enthält folgende Informationen:

- **Kundendaten**: Name, E-Mail, Telefon, Adresse
- **Offener Gesamtbetrag**: Summe aller unbezahlten Rechnungen
- **Unbezahlte Rechnungen**: Liste mit Rechnungsnummer, Datum, Fälligkeitsdatum, Status und offenen Beträgen
- **Letzte Transaktionen**: Zahlungen der letzten 3 Monate mit Datum, Beschreibung, Kategorie und Betrag

## Technische Details

### Controller

- `CostOverviews@index`: Liste aller Kunden
- `CostOverviews@show`: Detailansicht für einen Kunden
- `CostOverviews@email`: E-Mail-Versand
- `CostOverviews@pdf`: PDF-Download
- `CostOverviews@print`: Druck-Ansicht

### Jobs

- `SendCostOverview`: Versendet die Kostenübersicht per E-Mail
- `DownloadCostOverview`: Generiert das PDF für den Download

### Notification

- `CostOverview`: E-Mail-Benachrichtigung mit PDF-Anhang

## Anforderungen

- Akaunting 3.x oder höher
- PHP 8.1 oder höher
- Laravel 10.x oder höher
- DomPDF (bereits in Akaunting enthalten)

## Lizenz

MIT License

## Support

Bei Fragen oder Problemen erstellen Sie bitte ein Issue im GitHub-Repository.

---

# Cost Overview Module for Akaunting

A module for Akaunting that allows sending customers an overview of their current costs - via email or as a PDF export.

## Features

- **Customer Overview**: List of all customers with outstanding amounts
- **Detailed Cost Overview**: Shows unpaid invoices and recent transactions (3 months)
- **Email Sending**: Send the cost overview directly to the customer via email
- **PDF Export**: Export the overview as a PDF file
- **Print View**: Print the cost overview directly from the browser
- **Multilingual**: Supports German (de-DE) and English (en-GB)

## Installation

1. Copy the `CostOverview` folder to the `/modules/` directory of your Akaunting installation
2. Activate the module in the Akaunting admin interface under "Apps"
3. The module is now available at the URL `/cost-overviews`

## Usage

### View Cost Overview

1. Navigate to "Cost Overviews" in the admin menu
2. Select a customer from the list
3. The detailed cost overview will be displayed

### Send Email

1. Open a customer's cost overview
2. Click "Send Email"
3. The cost overview will be sent as a PDF to the customer's email address

### Export PDF

1. Open a customer's cost overview
2. Click "Download PDF"
3. The PDF will be automatically downloaded

### Print

1. Open a customer's cost overview
2. Click "Print"
3. A print-optimized view opens in a new tab

## Cost Overview Content

The cost overview contains the following information:

- **Customer Data**: Name, email, phone, address
- **Total Outstanding**: Sum of all unpaid invoices
- **Unpaid Invoices**: List with invoice number, date, due date, status, and outstanding amounts
- **Recent Transactions**: Payments from the last 3 months with date, description, category, and amount

## Technical Details

### Controllers

- `CostOverviews@index`: List of all customers
- `CostOverviews@show`: Detail view for a customer
- `CostOverviews@email`: Email sending
- `CostOverviews@pdf`: PDF download
- `CostOverviews@print`: Print view

### Jobs

- `SendCostOverview`: Sends the cost overview via email
- `DownloadCostOverview`: Generates the PDF for download

### Notification

- `CostOverview`: Email notification with PDF attachment

## Requirements

- Akaunting 3.x or higher
- PHP 8.1 or higher
- Laravel 10.x or higher
- DomPDF (already included in Akaunting)

## License

MIT License

## Support

For questions or problems, please create an issue in the GitHub repository.
