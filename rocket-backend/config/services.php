<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ocr' => [
        'url' => env('OCR_API_URL', 'http://127.0.0.1:9010'),
        'lang' => env('OCR_LANG', 'slk+eng'),
        'timeout' => (int) env('OCR_TIMEOUT', 180),
    ],

    'orchestrator' => [
        'url' => env('ORCHESTRATOR_URL', 'http://127.0.0.1:8001'),
        'token' => env('ORCHESTRATOR_TOKEN'),
        'timeout' => (int) env('ORCHESTRATOR_TIMEOUT', 110),
        'form_id' => env('ORCHESTRATOR_FORM_ID'),
        'mapping_id' => env('ORCHESTRATOR_MAPPING_ID'),
    ],

];
