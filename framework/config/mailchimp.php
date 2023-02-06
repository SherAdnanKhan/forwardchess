<?php

$apiKey = env('MAILCHIMP_APIKEY');

return [
    'apiKey'  => $apiKey,
    'server'  => 'us9',
    'storeId' => env('MAILCHIMP_STORE'),

    'lists' => [
        'subscribers'  => env('MAILCHIMP_SUBSCRIBERS_LIST'),
        'registration' => env('MAILCHIMP_REGISTRATION_LIST'),
    ],

    'enabledEmails' => [
//        'radudalbea@gmail.com',
//        'radudalbea1@gmail.com',
//        'callumwaddell0@gmail.com',
//        'Mongoosepress@gmail.com',
//        'callum@sendonomics.com'
    ],

    'automations' => [
        'browseAbandoned' => [
            'id'        => env('MAILCHIMP_BROWSE_ABANDONED_WORKFLOW_ID'),
            'emailId'   => env('MAILCHIMP_BROWSE_ABANDONED_WORKFLOW_EMAIL_ID'),
            'segmentId' => '11833906'
        ]
    ]
];
