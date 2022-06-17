<?php

use Foundation\Router\Router;

Router::get('/home', 'App\Http\Controllers\MainController@main');



/**
 * authorization
 */
Router::post('/auth/register', 'App\Http\Controllers\Auth\Authorization@register');
Router::post('/auth/login', 'App\Http\Controllers\Auth\Authorization@login');
/**
 * User
 */

Router::get('/users', 'App\Http\Controllers\UserCRUDController@index')->middleware();
Router::post('/users/search', 'App\Http\Controllers\UserCRUDController@index');
Router::post('/users', 'App\Http\Controllers\UserCRUDController@store');
Router::get('/users/{key}', 'App\Http\Controllers\UserCRUDController@show');
Router::put('/users/{key}', 'App\Http\Controllers\UserCRUDController@updated');
Router::delete('/users/{key}', 'App\Http\Controllers\UserCRUDController@destroy');

/**
 * Article
 */
Router::get('/articles', 'App\Http\Controllers\ArticleCRUDController@index');
Router::post('/articles/search', 'App\Http\Controllers\ArticleCRUDController@index');
Router::post('/articles', 'App\Http\Controllers\ArticleCRUDController@store');
Router::get('/articles/{key}', 'App\Http\Controllers\ArticleCRUDController@show');
Router::put('/articles/{key}', 'App\Http\Controllers\ArticleCRUDController@updated');
Router::delete('/articles/{key}', 'App\Http\Controllers\ArticleCRUDController@destroy');

/**
 * Video
 */
Router::get('/videos', 'App\Http\Controllers\VideoCRUDController@index');
Router::post('/videos/search', 'App\Http\Controllers\VideoCRUDController@index');
Router::post('/videos', 'App\Http\Controllers\VideoCRUDController@store');
Router::get('/videos/{key}', 'App\Http\Controllers\VideoCRUDController@show');
Router::put('/videos/{key}', 'App\Http\Controllers\VideoCRUDController@updated');
Router::delete('/videos/{key}', 'App\Http\Controllers\VideoCRUDController@destroy');

/**
 * Comment
 */
Router::get('/comments', 'App\Http\Controllers\CommentCRUDController@index');
Router::post('/comments/search', 'App\Http\Controllers\CommentCRUDController@index');
Router::post('/comments', 'App\Http\Controllers\CommentCRUDController@store');
Router::get('/comments/{key}', 'App\Http\Controllers\CommentCRUDController@show');
Router::put('/comments/{key}', 'App\Http\Controllers\CommentCRUDController@updated');
Router::delete('/comments/{key}', 'App\Http\Controllers\CommentCRUDController@destroy');


/**
 * Test data Vladislav
 */
Router::get('/projects', 'App\Http\Controllers\ProjectController@index');
Router::post('/projects/search', 'App\Http\Controllers\ProjectController@index');
Router::post('/projects', 'App\Http\Controllers\ProjectController@store');
Router::get('/projects/{key}', 'App\Http\Controllers\ProjectController@show');
Router::put('/projects/{key}', 'App\Http\Controllers\ProjectController@updated');
Router::delete('/projects/{key}', 'App\Http\Controllers\ProjectController@destroy');

Router::get('/organisations', 'App\Http\Controllers\OrganisationController@index');
Router::post('/organisations/search', 'App\Http\Controllers\OrganisationController@index');
Router::post('/organisations', 'App\Http\Controllers\OrganisationController@store');
Router::get('/organisations/{key}', 'App\Http\Controllers\OrganisationController@show');
Router::put('/organisations/{key}', 'App\Http\Controllers\OrganisationController@updated');
Router::delete('/organisations/{key}', 'App\Http\Controllers\OrganisationController@destroy');
