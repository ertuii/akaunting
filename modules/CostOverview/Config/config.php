<?php

return [
    'name' => 'CostOverview',
    'alias' => 'cost-overview',
    
    // Number of months to include in recent transactions
    'transaction_months' => 3,
    
    // Invoice statuses to include in cost overview
    'unpaid_statuses' => [
        'sent',
        'viewed',
        'partial',
    ],
    
    // Enable/disable features
    'features' => [
        'email' => true,
        'pdf' => true,
        'print' => true,
    ],
];
