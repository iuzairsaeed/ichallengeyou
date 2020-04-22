<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
    	Route::get('user', function (Request $request) {
            return $request->user();
        });
    });
});
