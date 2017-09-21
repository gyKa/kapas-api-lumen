<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'bookmark'], function () use ($router) {
    $router->get('/', ['uses' => 'BookmarkController@index']);
    $router->post('/', ['uses' => 'BookmarkController@store']);
    $router->get('/{id}', ['uses' => 'BookmarkController@show']);
    $router->put('/{id}', ['uses' => 'BookmarkController@update']);
    $router->delete('/{id}', ['uses' => 'BookmarkController@destroy']);
});

$router->group(['prefix' => 'tag'], function () use ($router) {
    $router->get('/', ['uses' => 'TagController@index']);
    $router->post('/', ['uses' => 'TagController@store']);
    $router->get('/{id}', ['uses' => 'TagController@show']);
    $router->put('/{id}', ['uses' => 'TagController@update']);
    $router->delete('/{id}', ['uses' => 'TagController@destroy']);
});
