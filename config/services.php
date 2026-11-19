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

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL', 'http://localhost:8001') . '/auth/google/callback'),
    ],

    'firebase' => [
        'api_key' => env('FIREBASE_API_KEY', 'AIzaSyAQPQMOR2BIyP1Cr60ByJ2YVDz3Kc_dkgM'),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'achaarg-8b104.firebaseapp.com'),
        'project_id' => env('FIREBASE_PROJECT_ID', 'achaarg-8b104'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'achaarg-8b104.firebasestorage.app'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', '87337351704'),
        'app_id' => env('FIREBASE_APP_ID', '1:87337351704:web:4a4f669317f0c516e1216d'),
        'measurement_id' => env('FIREBASE_MEASUREMENT_ID', 'G-DVNTJC1CRX'),
    ],

];
