<?php

return [
    'name' => 'Kostenübersichten',
    'singular' => 'Kostenübersicht',
    'description' => 'Kostenübersichten erstellen und verwalten',

    'document_number' => 'Kostenübersicht-Nummer',
    'budget' => 'Budget',
    'budget_utilization' => 'Budget-Auslastung',
    'budget_status' => 'Budget-Status',

    'status' => [
        'draft' => 'Entwurf',
        'sent' => 'Gesendet',
        'approved' => 'Vom Kunden bestätigt',
        'converted' => 'In Rechnung umgewandelt',
    ],

    'messages' => [
        'created' => 'Kostenübersicht erfolgreich erstellt!',
        'updated' => 'Kostenübersicht erfolgreich aktualisiert!',
        'deleted' => 'Kostenübersicht erfolgreich gelöscht!',
        'duplicated' => 'Kostenübersicht erfolgreich dupliziert!',
        'sent' => 'Kostenübersicht erfolgreich versendet!',
        'marked_sent' => 'Kostenübersicht als gesendet markiert!',
        'marked_approved' => 'Kostenübersicht als bestätigt markiert!',
        'converted' => 'Kostenübersicht erfolgreich in Rechnung umgewandelt!',
    ],

    'errors' => [
        'email_required' => 'Kunden-E-Mail ist erforderlich, um die Kostenübersicht zu senden!',
        'cannot_convert' => 'Diese Kostenübersicht kann nicht in eine Rechnung umgewandelt werden!',
    ],

    'actions' => [
        'send' => 'Kostenübersicht senden',
        'print' => 'Kostenübersicht drucken',
        'download' => 'PDF herunterladen',
        'duplicate' => 'Duplizieren',
        'convert_to_invoice' => 'In Rechnung umwandeln',
        'mark_sent' => 'Als gesendet markieren',
        'mark_approved' => 'Als bestätigt markieren',
    ],

    'budget_colors' => [
        'green' => 'Unter 50% - Gut',
        'yellow' => '50-80% - Warnung',
        'red' => 'Über 80% - Kritisch',
    ],
];
