<?php

return [
    'name' => 'Cost Overview',
    'version' => '1.0.0',
    
    // Default status values
    'statuses' => [
        'draft' => 'draft',
        'sent' => 'sent',
        'approved' => 'approved',
        'converted' => 'converted',
    ],
    
    // Budget threshold colors
    'budget_thresholds' => [
        'green' => 50,  // Under 50%
        'yellow' => 80, // 50-80%
        'red' => 100,   // Over 80%
    ],
];
