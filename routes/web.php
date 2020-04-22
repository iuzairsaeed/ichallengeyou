<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::group(['namespace' => 'Web', 'middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});
