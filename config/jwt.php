<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Secret & Settings
    |--------------------------------------------------------------------------
    */

    'secret' => env('JWT_SECRET', config('app.key')),

    'algo' => env('JWT_ALGO', 'HS256'),

    // Access token time-to-live in seconds (default: 1 hour)
    'ttl' => (int) env('JWT_TTL', 3600),

    // Refresh token time-to-live in seconds (default: 14 days)
    'refresh_ttl' => (int) env('JWT_REFRESH_TTL', 1209600),
];

