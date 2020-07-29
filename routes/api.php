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
            Route::post('{challenge}/likeChallenge', 'ChallengeController@likeChallenge');
            Route::post('{challenge}/unlikeChallenge', 'ChallengeController@unlikeChallenge');
            Route::post('{challenge}/favorite', 'ChallengeController@favoriteChallenge');
            Route::get('/acceptedList', 'AcceptedChallengeController@acceptedChallenge');
            Route::post('{challenge}/accept', 'AcceptedChallengeController@accept');
            Route::get('/donatedList', 'DonatedChallengeController@donatedChallenge');
            Route::post('{comment}/likeComment', 'ChallengeController@likeComment');
            Route::post('{comment}/unlikeComment', 'ChallengeController@unlikeComment');
        });
        Route::post('{comment}/deleteComment', 'ChallengeController@deleteComment');
        Route::post('{comment}/restoreComment', 'ChallengeController@restoreComment');

        Route::resource('categories', 'CategoryController', ['except' => ['index']]);
        Route::resource('favorites', 'FavoriteController');

        Route::group(['prefix' => 'user'], function () {
            Route::put('updateProfile', 'UserController@updateProfile');
            Route::put('updateAvatar', 'UserController@updateAvatar');
        });

        Route::group(['prefix' => 'transaction'], function () {
            Route::get('history', 'TransactionController@history');
            Route::post('withdraw', 'TransactionController@withdraw');
            Route::post('paypal/addBalance', 'PaymentController@loadBalance');
        });
        
        Route::group(['prefix' => 'submit'], function () {
            Route::get('{acceptedChallenge}/detail', 'SubmitChallengeController@getSubmitChallengeDetail');
            Route::get('{challenge}', 'SubmitChallengeController@getSubmitChallengerList');
            Route::post('{challenge}', 'SubmitChallengeController@postSubmitChallenge');
            Route::get('{challenge}/getVideo', 'SubmitChallengeController@getVideo');
            Route::post('{challenge}/addVideo', 'SubmitChallengeController@addVideo');
            Route::delete('{file}/deleteVideo', 'SubmitChallengeController@deleteVideo');
            Route::post('{file}/getVideo', 'SubmitChallengeController@deleteVideo');
        });

        Route::group(['prefix' => 'vote'], function () {
            Route::post('{submitedChallenge}/up', 'VoteController@voteUp');
            Route::post('{submitedChallenge}/down', 'VoteController@voteDown');
            Route::get('{challenge}/result', 'VoteController@result');
        });

        Route::resource('notification', 'NotificationController');
        
        Route::group(['prefix' => 'bid'], function () {
            Route::get('{challenge}/list', 'BidController@index');
            Route::post('{challenge}', 'BidController@store');
        });
        Route::get('user/list', 'UserController@getAllUsers');
        Route::group(['prefix' => 'ask'], function () {
            Route::get('/', 'AskCandidateController@index');
            Route::post('{challenge}', 'AskCandidateController@store');
            Route::get('{challenge}/result', 'AskCandidateController@result');
        });
        Route::get('/btc/token' , function () {
            btcAuth();
        });
        Route::post('/btc/invoice' , function () {
            btcInvoice();
        });
    });
            Route::get('/inquery' , function () {
                return ($_REQUEST);
            });
});
