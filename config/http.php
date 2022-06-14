<?php

return [
    'middlewares' => [
        'global' => [
            \App\Http\Middlewares\TestMiddleware::class,
            \App\Http\Middlewares\ExampleMiddleware::class,
            \App\Http\Middlewares\AuthMiddleware::class,
        ],
    ]
];
