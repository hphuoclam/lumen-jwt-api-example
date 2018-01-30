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
$router->group(['prefix' => 'api'], function () use ($router) {
	// login
    $router->POST('/login', 'AuthController@loginPost');

    // profile
    $router->GET('/profile', 'AuthController@index');

    // get list user
    $router->GET('/users', 'AuthController@lists');

    // posts
    $router->group(['prefix' => 'posts'], function () use ($router) {
	    $router->GET('/', 'PostController@index');
		$router->POST('/', 'PostController@create');
		$router->GET('/{postId}', 'PostController@details');
		$router->PUT('/{postId}', 'PostController@update');
	});
});