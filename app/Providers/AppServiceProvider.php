<?php

namespace App\Providers;

use View;
use App\Models\User;
use App\Models\Vote;
use App\Models\Setting;
use App\Models\Comment;
use App\Models\Constant;
use App\Models\Challenge;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\SubmitChallenge;
use App\Observers\UserObserver;
use App\Observers\VoteObserver;
use App\Observers\AmountObserver;
use App\Observers\CommentObserver;
use App\Observers\ChallengeObserver;
use App\Observers\TransactionObserver;
use App\Observers\SubmitChallengeObserver;
use App\Console\Commands\ModelMakeCommand;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // For SubmitChallenge CRUD observer
        SubmitChallenge::observe(SubmitChallengeObserver::class);
        // For SubmitChallenge CRUD observer
        Vote::observe(VoteObserver::class);
        // For Transaction CRUD observer
        Transaction::observe(TransactionObserver::class);
        // For Challenge CRUD observer
        Challenge::observe(ChallengeObserver::class);
        // For Comment CRUD observer
        Comment::observe(CommentObserver::class);

        // View Notifications on Admin Web
        view()->composer('inc.navbar', function($view){
            $view->with('notifications', Notification::where('user_id' , auth()->id() )->latest()->limit(8)->get());
        });
    }
}
