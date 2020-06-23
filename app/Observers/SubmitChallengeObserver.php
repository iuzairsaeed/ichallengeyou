<?php

namespace App\Observers;

use Illuminate\Http\Request;
use App\Models\SubmitChallenge;
use App\Models\Challenge;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\ChallengeSubmited;

class SubmitChallengeObserver
{
    /**
     * Handle the submit challenge "created" event.
     *
     * @param  \App\SubmitChallenge  $submitChallenge
     * @return void
     */
    public function created(SubmitChallenge $submitChallenge)
    {
        try {
            $donators = $submitChallenge->acceptedChallenge->challenge->donations()->get();
            $user = $submitChallenge->acceptedChallenge->user;
            $challenge = $submitChallenge->acceptedChallenge->challenge;        
            $notificationModels = [];
            foreach ($donators as $donator) {
                $notification[] = new Notification([
                    'challenge_id' => $challenge->id,
                    'user_id' => $donator->user->id,
                    'title' => 'Challenge Submited', 
                    'body' => $user->name.' has been Submited the Challenge '.$challenge->title, 
                ]);
                $donator->user->notify(new ChallengeSubmited);
            }
            $submitChallenge->acceptedChallenge->challenge->notifications()->saveMany($notification);
        } catch (\Throwable $th) {
            return response('No Donator Found' , 204);
        }
    }

    /**
     * Handle the submit challenge "updated" event.
     *
     * @param  \App\SubmitChallenge  $submitChallenge
     * @return void
     */
    public function updated(SubmitChallenge $submitChallenge)
    {
        //
    }

    /**
     * Handle the submit challenge "deleted" event.
     *
     * @param  \App\SubmitChallenge  $submitChallenge
     * @return void
     */
    public function deleted(SubmitChallenge $submitChallenge)
    {
        //
    }

    /**
     * Handle the submit challenge "restored" event.
     *
     * @param  \App\SubmitChallenge  $submitChallenge
     * @return void
     */
    public function restored(SubmitChallenge $submitChallenge)
    {
        //
    }

    /**
     * Handle the submit challenge "force deleted" event.
     *
     * @param  \App\SubmitChallenge  $submitChallenge
     * @return void
     */
    public function forceDeleted(SubmitChallenge $submitChallenge)
    {
        //
    }
}
