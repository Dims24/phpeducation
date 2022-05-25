<?php

use Foundation\Router\Router;

Router::get('/home', 'App\Http\Controllers\MainController@main');

Router::get('/projects', 'App\Http\Controllers\ProjectController@index');
Router::get('/projects/{project}', 'App\Http\Controllers\ProjectController@show');

Router::post('/home', 'App\Http\Controllers\MainController@main');
//Router::post('/home/{test}/test/{key}', '\MyProject\Controllers\MainController@main');
