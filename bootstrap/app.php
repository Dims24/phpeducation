<?php

require_once 'helpers.php';

spl_autoload_register(function (string $className) {
    require_once __DIR__ . '/../app/' . str_replace('\\', '/', $className) . '.php';
});

$app = new Application();

return $app;