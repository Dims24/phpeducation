<?php

/** @var App\Foundation\Application $app */
$app = App\Foundation\Application::getInstance();

$app->setRootPath($_ENV['APP_BASE_PATH'] ?? dirname(__DIR__));

return $app;
