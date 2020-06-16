<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Console\Commands\ModelMakeCommand;
use App\Models\User;
use App\Models\SubmitChallenge;
use App\Models\Setting;
use App\Models\Constant;
use App\Observers\UserObserver;
use App\Observers\SubmitChallengeObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->app->extend('command.model.make', function ($command, $app) {
            return new ModelMakeCommand($app['files']);
        });

        // Loading settings from database into configuration
        if (Schema::hasTable('settings')) {
            config([
                'global' => Setting::all([
                    'name', 'value'
                ])
                ->keyBy('name') // key every setting by its name
                ->transform(function ($setting) {
                     return $setting->value; // return only the value
                })
                ->toArray() // make it an array
            ]);
        }

        //
        config([
            'global.DATE_FORMAT' => Constant::DATE_FORMAT
        ]);

        // Registerign user CRUD observer
        User::observe(UserObserver::class);

        // \App\Models\SubmitChallenge::creating(function($model){
        //     $model->accepted_challenge_id = 11111;
        // });

        \App\Models\SubmitChallenge::observe(\App\Observers\SubmitChallengeObserver::class);
        // For SubmitChallenge CRUD observer
        // SubmitChallenge::observe(SubmitChallengeObserver::class);
    }
}
