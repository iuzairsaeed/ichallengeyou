<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::put('forgotPassword', 'AuthController@forgotPassword');
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::resource('challenges', 'ChallengeController');
        Route::group(['prefix' => 'challenge'], function () {
            Route::get('getTrending', 'ChallengeController@getTrending');
            Route::post('{challenge}/donation', 'ChallengeController@donation');
            Route::post('{challenge}/comment', 'ChallengeController@comment');
            Route::post('{challenge}/like', 'ChallengeController@like');
            Route::post('{challenge}/unlike', 'ChallengeController@unlike');
            Route::post('{challenge}/favorite', 'ChallengeController@favorite');
        });

        Route::resource('categories', 'CategoryController');
        Route::resource('favorites', 'FavoriteController');

        Route::group(['prefix' => 'user'], function () {
            Route::put('changePassword', 'AuthController@changePassword');
            Route::put('updateProfile', 'UserController@updateProfile');
            Route::put('updateAvatar', 'UserController@updateAvatar');
        });
    });
});
