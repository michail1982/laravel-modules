<?php
return [
    'provider' => env('MODULES_PROVIDER', 'filesystem'),
    'filesystem' => [
        'paths' => app_path('Modules'),
        'depth' => env('MODULES_SCAN_DEPTH', 2),
    ],
    'cache' => [
        'enabled' => !env('APP_DEBUG', true),
        'key' => env('MODULES_CACHE_KEY', 'modules')
    ]
];