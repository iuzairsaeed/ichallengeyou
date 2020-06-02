<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::put('forgotPassword', 'AuthController@forgotPassword');
    });

    Route::resource('challenges', 'ChallengeController', ['except' => ['create', 'update', 'destroy']]);
    Route::get('categories', 'CategoryController@index');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('logout', 'AuthController@logout');
        });

        Route::resource('challenges', 'ChallengeController', ['except' => ['index', 'show']]);
        Route::group(['prefix' => 'challenge'], function () {
            Route::get('/acceptedChallenge', 'AcceptedChallengeController@acceptedChallenge');
            Route::get('/donatedChallenge', 'DonatedChallengeController@donatedChallenge');
            Route::get('/myList', 'ChallengeController@myList');
            Route::post('{challenge}/donation', 'ChallengeController@donation');
            Route::get('{challenge}/comments', 'ChallengeController@comments');
            Route::post('{challenge}/comment', 'ChallengeController@comment');
            Route::post('{challenge}/like', 'ChallengeController@like');
            Route::post('{challenge}/unlike', 'ChallengeController@unlike');
            Route::post('{challenge}/favorite', 'ChallengeController@favorite');
        });

        Route::resource('categories', 'CategoryController', ['except' => ['index']]);
        Route::resource('favorites', 'FavoriteController');

        Route::group(['prefix' => 'user'], function () {
            Route::put('changePassword', 'AuthController@changePassword');
            Route::put('updateProfile', 'UserController@updateProfile');
            Route::put('updateAvatar', 'UserController@updateAvatar');
        });
    });
});
