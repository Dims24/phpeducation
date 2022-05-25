<?php

use Foundation\Router\Router;

Router::get('/home', 'App\Http\Controllers\MainController@main');
Router::post('/home', 'App\Http\Controllers\MainController@main');
//Router::post('/home/{test}/test/{key}', '\MyProject\Controllers\MainController@main');