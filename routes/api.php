<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::put('forgotPassword', 'AuthController@forgotPassword');

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::get('user', 'AuthController@user');
            Route::put('changePassword', 'AuthController@changePassword');
        });
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('challenges/getList', 'ChallengeController@getList');
    });
});
