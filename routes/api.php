<?php
$router = new \Bramus\Router\Router();


$router->get('/articles', 'App\Http\Controllers\ArticleCRUDController@index');
$router->post('/articles/search', 'App\Http\Controllers\ArticleCRUDController@index');

$router->run();