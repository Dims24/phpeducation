<?php

require_once 'helpers.php';

spl_autoload_register(function (string $className) {
    $className = str_replace('App\\', '', $className);
    require_once __DIR__ . '/../app/' . str_replace('\\', '/', $className) . '.php';
});

set_exception_handler([
    App\Foundation\Exception\ExceptionHandler::class,
    'handleException'
]);

set_error_handler([
    App\Foundation\Exception\ExceptionHandler::class,
    'handleError'
]);

$app = new App\Foundation\Application();

return $app;