<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Web'], function () {
    Auth::routes();

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    });

    Route::get('{any}', 'PagesController@home')->where('any', '.*');
});
