<?php

return [
    'name' => 'Cost Overviews',
    'singular' => 'Cost Overview',
    'description' => 'Create and manage cost overviews',

    'document_number' => 'Cost Overview Number',
    'budget' => 'Budget',
    'budget_utilization' => 'Budget Utilization',
    'budget_status' => 'Budget Status',

    'status' => [
        'draft' => 'Draft',
        'sent' => 'Sent',
        'approved' => 'Approved',
        'converted' => 'Converted to Invoice',
    ],

    'messages' => [
        'created' => 'Cost overview created successfully!',
        'updated' => 'Cost overview updated successfully!',
        'deleted' => 'Cost overview deleted successfully!',
        'duplicated' => 'Cost overview duplicated successfully!',
        'sent' => 'Cost overview sent successfully!',
        'marked_sent' => 'Cost overview marked as sent!',
        'marked_approved' => 'Cost overview marked as approved!',
        'converted' => 'Cost overview converted to invoice successfully!',
    ],

    'errors' => [
        'email_required' => 'Customer email is required to send cost overview!',
        'cannot_convert' => 'This cost overview cannot be converted to invoice!',
    ],

    'actions' => [
        'send' => 'Send Cost Overview',
        'print' => 'Print Cost Overview',
        'download' => 'Download PDF',
        'duplicate' => 'Duplicate',
        'convert_to_invoice' => 'Convert to Invoice',
        'mark_sent' => 'Mark as Sent',
        'mark_approved' => 'Mark as Approved',
    ],

    'budget_colors' => [
        'green' => 'Under 50% - Good',
        'yellow' => '50-80% - Warning',
        'red' => 'Over 80% - Critical',
    ],
];
