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
            $creater = $submitChallenge->acceptedChallenge->challenge->user;
            $challenge = $submitChallenge->acceptedChallenge->challenge;        
            foreach ($donators as $donator) {
                $notification[] = new Notification([
                    'user_id' => $donator->user->id,
                    'title' => 'Challenge Submited', 
                    'body' => $user->name.' has been Submited the Challenge '.$challenge->title, 
                    'click_action' =>'SUBMITED_CHALLENGE_DETAIL_SCREEN', 
                    'data_id' => $submitChallenge->accepted_challenge_id, 
                ]);
                $donator->user->notify(new ChallengeSubmited($submitChallenge->accepted_challenge_id));
            }
            $submitChallenge->notifications()->saveMany($notification);

            $createrNotification = new Notification([
                'user_id' => $creater->id,
                'title' => 'Challenge Submited', 
                'body' => $user->name.' has been Submited the Challenge '.$challenge->title, 
                'click_action' =>'SUBMITED_CHALLENGE_DETAIL_SCREEN', 
                'data_id' => $submitChallenge->accepted_challenge_id, 
            ]);
            $submitChallenge->notifications()->save($createrNotification);  
            $creater->notify(new ChallengeSubmited($submitChallenge->accepted_challenge_id));

        } catch (\Throwable $th) {
            return response($th->getMessage() , 400);
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
