<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::put('forgotPassword', 'AuthController@forgotPassword');
    });

    Route::resource('challenges', 'ChallengeController', ['except' => ['create', 'update', 'destroy']]);
    Route::get('categories', 'CategoryController@index', ['only' => ['index']]);
    
    Route::group(['prefix' => 'challenge'], function () {
        Route::get('{challenge}/comments', 'ChallengeController@comments');
    });
   
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::get('user', 'AuthController@user');
            Route::put('changePassword', 'AuthController@changePassword');
            Route::post('logout', 'AuthController@logout');
        });

        Route::resource('challenges', 'ChallengeController', ['except' => ['index', 'show']]);
        Route::group(['prefix' => 'challenge'], function () {
            Route::get('/myList', 'ChallengeController@myList');
            Route::post('{challenge}/donation', 'ChallengeController@donation');
            Route::post('{challenge}/comment', 'ChallengeController@comment');
            Route::post('{challenge}/like', 'ChallengeController@like');
            Route::post('{challenge}/unlike', 'ChallengeController@unlike');
            Route::post('{challenge}/favorite', 'ChallengeController@favorite');
            Route::get('/acceptedList', 'AcceptedChallengeController@acceptedChallenge');
            Route::post('{challenge}/accept', 'AcceptedChallengeController@accept');
            Route::get('/donatedList', 'DonatedChallengeController@donatedChallenge');
        });

        Route::resource('categories', 'CategoryController', ['except' => ['index']]);
        Route::resource('favorites', 'FavoriteController');

        Route::group(['prefix' => 'user'], function () {
            Route::put('updateProfile', 'UserController@updateProfile');
            Route::put('updateAvatar', 'UserController@updateAvatar');
        });

        Route::group(['prefix' => 'transaction'], function () {
            Route::get('history', 'TransactionController@history');
            Route::post('paypal/addBalance', 'PaymentController@loadBalance');
        });
        
        Route::group(['prefix' => 'submit'], function () {
            Route::get('{challenge}', 'SubmitChallengeController@getSubmitChallenge');
            Route::post('{challenge}', 'SubmitChallengeController@postSubmitChallenge');
            Route::get('{challenge}/getVideo', 'SubmitChallengeController@getVideo');
            Route::post('{challenge}/addVideo', 'SubmitChallengeController@addVideo');
            Route::delete('{file}/deleteVideo', 'SubmitChallengeController@deleteVideo');
            Route::post('{file}/getVideo', 'SubmitChallengeController@deleteVideo');
        });

    });
});
