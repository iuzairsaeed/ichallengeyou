<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Web'], function () {
    Auth::routes(['register' => false]);

    Route::group(['middleware' => ['auth', 'checkRole:'.Admin()]], function () {
        Route::get('dashboard', 'DashboardController@index')->name('dashboard');
        Route::get('changePassword','ProfileController@showChangePasswordForm');
        Route::post('changePassword','ProfileController@changePassword')->name('changePassword');
        Route::get('profile','ProfileController@showProfileForm');
        Route::post('profile','ProfileController@profile')->name('profile');

        Route::resource('challenges','ChallengeController');
        Route::get('challengesList', 'ChallengeController@getList')->name('challenges.getList');
        Route::get('{challenge}/challengesBids', 'ChallengeController@getBids')->name('challenges.getBids');
        Route::get('{challenge}/challengesDonations', 'ChallengeController@getDonations')->name('challenges.getDonations');
        Route::get('{challenge}/challengesAcceptors', 'AcceptedChallengeController@getAcceptors')->name('challenges.getAcceptors');
        Route::get('{challenge}/submitedChallenge', 'AcceptedChallengeController@submitedChallenge');
        Route::get('{challenge}/challengesSubmitors', 'AcceptedChallengeController@getSubmitors')->name('challenges.getSubmitors');
        Route::resource('users','UserController');
        Route::get('usersList', 'UserController@getList')->name('users.getList');
        Route::resource('settings','SettingController');
        Route::get('settingsList', 'SettingController@getList')->name('settings.getList');
        Route::resource('amounts','AmountController');
        Route::get('amountsList', 'AmountController@getList')->name('amounts.getList');
    });
});

Route::get('terms_conditions', 'Web\PageController@terms_conditions')->where('any', '.*');
Route::get('{any}', 'Web\PageController@home')->where('any', '.*');
