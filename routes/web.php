<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {
    Route::group(['middleware' => ['auth', 'checkRole:'.Admin()]], function () {
        Route::get('challenges/getList', 'ChallengeController@getList')->name('challenges.getList');
        Route::get('users/getList', 'UserController@getList')->name('users.getList');
    });
});

Route::group(['namespace' => 'Web'], function () {
    Auth::routes(['register' => false]);

    Route::group(['middleware' => ['auth', 'checkRole:'.Admin()]], function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('changePassword','ProfileController@showChangePasswordForm');
        Route::post('changePassword','ProfileController@changePassword')->name('changePassword');

        Route::resource('challenges','ChallengeController');
        Route::resource('users','UserController');
    });
});

Route::get('{any}', 'Web\PageController@home')->where('any', '.*');
