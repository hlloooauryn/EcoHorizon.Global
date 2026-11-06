<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | External Weather API (Open-Meteo & Marine Forecasts)
    |--------------------------------------------------------------------------
    */
    'open_meteo' => [
        'url' => env('OPEN_METEO_BASE_URL', 'https://api.open-meteo.com/v1/forecast'),
        'timeout' => (int) env('OPEN_METEO_TIMEOUT', 3),
        'api_key' => env('OPEN_METEO_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maps & GIS Tile Services (OpenStreetMap / Mapbox)
    |--------------------------------------------------------------------------
    */
    'maps' => [
        'tile_url' => env('MAP_TILE_URL', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),
        'attribution' => env('MAP_ATTRIBUTION', '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'),
        'api_key' => env('MAP_API_KEY'),
        'mapbox_token' => env('MAPBOX_ACCESS_TOKEN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Carbon Emission & GLEC Framework Calculation Engine
    |--------------------------------------------------------------------------
    */
    'glec' => [
        'api_key' => env('GLEC_API_KEY'),
        'endpoint_url' => env('GLEC_API_ENDPOINT'),
        'factor_source' => env('GLEC_FACTOR_SOURCE', 'GLEC Framework v3.0'),
    ],

];
