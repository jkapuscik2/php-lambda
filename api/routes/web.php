<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/get', function (Illuminate\Http\Request $request) use ($router) {
    return response([
        "version" => $router->app->version(),
        'POST' => $_POST,
        'GET' => $_GET
    ], 200, [
        "Content-Type" => "application/json"
    ]);
});

$router->post('/post', function (Illuminate\Http\Request $request) use ($router) {
    return response([
        "version" => $router->app->version(),
        'POST' => $_POST,
        'GET' => $_GET
    ], 200, [
        "Content-Type" => "application/json"
    ]);
});
