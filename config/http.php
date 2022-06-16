<?php

return [
    'middlewares' => [
        'global' => [
            \App\Http\Middlewares\AuthMiddleware::class,
        ],
    ]
];
