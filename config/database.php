<?php

return [
    'connection' => [
        'database' => env('DB_DATABASE', 'phptest'),
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', 5432),
        'username' => env('DB_USERNAME', 'dataphp'),
        'password' => env('DB_PASSWORD', '1234')
    ]
];
