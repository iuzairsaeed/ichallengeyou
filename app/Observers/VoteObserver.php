<?php

namespace App\Observers;

use App\Models\Vote;
use Illuminate\Http\Request;
use App\Models\SubmitChallenge;
use App\Models\Challenge;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\VoteNotification;

class VoteObserver
{
    /**
     * Handle the vote "created" event.
     *
     * @param  \App\Vote  $vote
     * @return void
     */
    public function created(Vote $vote)
    {
        try {
            $voter = $vote->user;
            $user = $vote->submitChallenges->acceptedChallenge->user;
            $challenge = $vote->submitChallenges->acceptedChallenge->challenge;
            $notificationModels = [];
            $notification = new Notification([
                'user_id' => $user->id,
                'title' => 'Check Your Votes', 
                'body' => $voter->name.' has Vote You on the Submited Challenge '.$challenge->title, 
                'click_action' => 'CHALLENGE_DETAIL_SCREEN',
            ]);
            $user->notify(new VoteNotification($vote->submitChallenges->accepted_challenge_id));
            $vote->notifications()->save($notification);
        } catch (\Throwable $th) {
            return response('No Votes Found' , 404);
        }
    }

    /**
     * Handle the vote "updated" event.
     *
     * @param  \App\Vote  $vote
     * @return void
     */
    public function updated(Vote $vote)
    {
        //
    }

    /**
     * Handle the vote "deleted" event.
     *
     * @param  \App\Vote  $vote
     * @return void
     */
    public function deleted(Vote $vote)
    {
        //
    }

    /**
     * Handle the vote "restored" event.
     *
     * @param  \App\Vote  $vote
     * @return void
     */
    public function restored(Vote $vote)
    {
        //
    }

    /**
     * Handle the vote "force deleted" event.
     *
     * @param  \App\Vote  $vote
     * @return void
     */
    public function forceDeleted(Vote $vote)
    {
        //
    }
}