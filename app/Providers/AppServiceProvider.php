<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Console\Commands\ModelMakeCommand;
use App\Models\User;
use App\Models\Setting;
use App\Observers\UserObserver;

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
        // Schema::defaultStringLength(191);
        $this->app->extend('command.model.make', function ($command, $app) {
            return new ModelMakeCommand($app['files']);
        });

        // Loading settings from database into configuration
        config([
            'global' => Setting::all([
                'name','value'
            ])
            ->keyBy('name') // key every setting by its name
            ->transform(function ($setting) {
                 return $setting->value; // return only the value
            })
            ->toArray() // make it an array
        ]);

        // Registerign user CRUD observer
        User::observe(UserObserver::class);
    }
}
