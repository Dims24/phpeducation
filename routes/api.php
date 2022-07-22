<?php
//$router = new \Bramus\Router\Router();
//
//
//$router->get('/articles', 'App\Http\Controllers\ArticleCRUDController@index');
//$router->post('/articles/search', 'App\Http\Controllers\ArticleCRUDController@index');
//
//$router->run();
//use App\Foundation\Router\Router;

use Bramus\Router\Router;

$new = new Router();

$new->get('/articles', 'App\Http\Controllers\ArticleCRUDController@index');
$new->get('/article', 'App\Http\Controllers\ArticleCRUDController@index');
return $new;
//Router::get('/articles', 'App\Http\Controllers\ArticleCRUDController@index');
//Router::post('/articles/search', 'App\Http\Controllers\ArticleCRUDController@index');
//Router::post('/articles', 'App\Http\Controllers\ArticleCRUDController@store')->middleware(\App\Http\Middlewares\AuthMiddleware::class);
//Router::get('/articles/{key}', 'App\Http\Controllers\ArticleCRUDController@show');
//Router::put('/articles/{key}', 'App\Http\Controllers\ArticleCRUDController@updated')->middleware(\App\Http\Middlewares\AuthMiddleware::class);
//Router::delete('/articles/{key}', 'App\Http\Controllers\ArticleCRUDController@destroy')->middleware(\App\Http\Middlewares\AuthMiddleware::class);
