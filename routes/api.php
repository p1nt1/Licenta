<?php

Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');
    Route::get('get-user-by-activation-token/{token}', 'AuthController@getUserByActivationToken');
    Route::post('set-password', 'PasswordResetController@changePassword');
});

Route::group([
    'middleware' => 'auth:api',
    'namespace' => 'Auth',
    'prefix' => 'auth'
], function() {
    Route::post('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@user');
});

Route::group([
    'prefix' => 'user'
], function () {
    Route::get('get', 'UserController@get');
    Route::get('find/{id}', 'UserController@find');
    Route::delete('delete/{id}', 'UserController@delete');
    Route::put('edit/{id}', 'UserController@edit');
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'posts'
], function () {
    Route::post('/create', 'PostController@create');
});

Route::group([
    'prefix' => 'posts'
], function () {
    Route::get('get', 'PostController@get');
    Route::get('getAll', 'PostController@getAll');
    Route::get('find/{id}', 'PostController@find');
    Route::delete('delete/{id}', 'PostController@delete');
    Route::put('edit/{id}', 'PostController@edit');
    Route::get('/search', 'PostController@search');
    Route::post('status', 'PostController@status');
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'client'
], function () {
    Route::get('get', 'ClientController@get');
    Route::get('find/{id}', 'ClientController@find');
    Route::delete('delete/{id}', 'ClientController@delete');
    Route::put('edit/{id}', 'ClientController@edit');
    Route::post('/create', 'ClientController@create');
    Route::get('/search', 'ClientController@search');
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'picture'
], function () {
    Route::get('get', 'PictureController@get');
    Route::get('find/{id}', 'PictureController@find');
    Route::delete('delete/{id}', 'PictureController@delete');
    Route::post('/create', 'PictureController@create');
    Route::get('/search', 'PictureController@search');
    Route::post('/operation', 'PictureController@pictureOperation');
});

Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});
