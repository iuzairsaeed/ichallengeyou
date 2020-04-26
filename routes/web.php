<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Web'], function () {
    Auth::routes(['register' => false]);

    Route::group(['middleware' => ['auth', 'checkRole:'.Admin()]], function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('changePassword','ProfileController@showChangePasswordForm');
        Route::post('changePassword','ProfileController@changePassword')->name('changePassword');

        Route::resource('challenges','ChallengeController');
        Route::get('challenge/getRecords', 'ChallengeController@getRecords')->name('challenges.getRecords');
    });

    Route::get('{any}', 'PagesController@home')->where('any', '.*');
});
