<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');

        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::get('user', 'AuthController@user');
        });
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('challenges/getList', 'ChallengeController@getList');
    });
});
