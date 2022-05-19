<?php

use Router\Router;

Router::get('/home', '\MyProject\Controllers\MainController@main');
Router::get('/home/{test}/test/{key}', '\MyProject\Controllers\MainController@main');