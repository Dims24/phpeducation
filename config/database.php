<?php

return [
    'connection' => [
        'database' => env('DB_DATABASE', 'testapp'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', 5432),
        'username' => env('DB_USERNAME', 'postgres'),
        'password' => env('DB_PASSWORD', 'secret')
    ]
];
