<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {
    Route::group(['middleware' => ['auth', 'checkRole:'.Admin()]], function () {
        Route::get('challengesList', 'ChallengeController@index')->name('challengesList');
    });
});

Route::group(['namespace' => 'Web'], function () {
    Auth::routes(['register' => false]);

    Route::group(['middleware' => ['auth', 'checkRole:'.Admin()]], function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('changePassword','ProfileController@showChangePasswordForm');
        Route::post('changePassword','ProfileController@changePassword')->name('changePassword');
        Route::get('profile','ProfileController@showProfileForm');
        Route::post('profile','ProfileController@profile')->name('profile');

        Route::resource('challenges','ChallengeController');
        Route::resource('users','UserController');
        Route::get('usersList', 'UserController@getList')->name('users.getList');
    });
});

Route::get('{any}', 'Web\PageController@home')->where('any', '.*');
