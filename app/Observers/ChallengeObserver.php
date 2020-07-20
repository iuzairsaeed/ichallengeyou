<?php

namespace App\Observers;

use App\Models\Challenge;

class ChallengeObserver
{
    /**
     * Handle the challenges "created" event.
     *
     * @param  \App\challenges  $challenge
     * @return void
     */
    public function created(Challenge $challenge)
    {
        // TO CHALLENGE OWNER
        Notification::create([
            'user_id' => $challenge->user_id, 
            'title' => 'New Challenge Created', 
            'body' => 'You have Created The Challenge '.$challenge->name, 
            'click_action' =>'CHALLENGE_DETAIL_SCREEN', 
            'data_id' => $challenge->id, 
        ]);
        $challenge->user->notify(new ChallengeNotification($challenge->id,$challenge->name));
        // TO ADMIN
        Notification::create([
            'user_id' => 1, 
            'title' => 'New Challenge Created', 
            'body' => $challenge->user->name.' have Created The Challenge '.$challenge->name, 
            'click_action' =>'CHALLENGE_DETAIL_SCREEN', 
            'data_id' => $challenge->id, 
        ]);
    }

    /**
     * Handle the challenges "updated" event.
     *
     * @param  \App\challenges  $challenge
     * @return void
     */
    public function updated(Challenge $challenge)
    {
        //
    }

    /**
     * Handle the challenges "deleted" event.
     *
     * @param  \App\challenges  $challenge
     * @return void
     */
    public function deleted(Challenge $challenge)
    {
        //
    }

    /**
     * Handle the challenges "restored" event.
     *
     * @param  \App\challenges  $challenge
     * @return void
     */
    public function restored(Challenge $challenge)
    {
        //
    }

    /**
     * Handle the challenges "force deleted" event.
     *
     * @param  \App\challenges  $challenge
     * @return void
     */
    public function forceDeleted(Challenge $challenge)
    {
        //
    }
}
