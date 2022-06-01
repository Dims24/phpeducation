<?php

use Foundation\Router\Router;

Router::get('/home', 'App\Http\Controllers\MainController@main');

Router::get('/articles', 'App\Http\Controllers\ArticleCRUDController@index');
Router::post('/articles', 'App\Http\Controllers\ArticleCRUDController@store');
Router::get('/articles/{article}', 'App\Http\Controllers\ArticleCRUDController@show');
Router::put('/articles/{article}', 'App\Http\Controllers\ArticleCRUDController@updated');
Router::delete('/articles/{article}', 'App\Http\Controllers\ArticleCRUDController@destroy');

Router::get('/projects', 'App\Http\Controllers\ProjectController@index');
Router::post('/projects', 'App\Http\Controllers\ProjectController@store');
Router::get('/projects/{key}', 'App\Http\Controllers\ProjectController@show');
Router::put('/projects/{key}', 'App\Http\Controllers\ProjectController@updated');
Router::delete('/projects/{key}', 'App\Http\Controllers\ProjectController@destroy');
