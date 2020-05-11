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
        Route::resource('challenges', 'ChallengeController');
        Route::group(['prefix' => 'challenges'], function () {
            Route::post('{challenge}/comment', 'ChallengeController@comment');
            Route::post('{challenge}/like', 'ChallengeController@like');
            Route::post('{challenge}/unlike', 'ChallengeController@unlike');
            Route::post('{challenge}/favourite', 'ChallengeController@favourite');
        });
    });
});
