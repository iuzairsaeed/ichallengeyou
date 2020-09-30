<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Challenge;
use App\Models\Notification;
use App\Notifications\ChallengeNotification;
use Notification as Notifications;

class ChallengeObserver
{
    protected $users = [];
    /**
     * Handle the challenges "created" event.
     *
     * @param  \App\challenges  $challenge
     * @return void
     */
    public function created(Challenge $challenge)
    {
        // TO CHALLENGE OWNER
        $notification = new Notification([
            'user_id' => $challenge->user_id, 
            'title' => 'New Challenge Created', 
            'body' => 'You have Created The Challenge '.$challenge->name, 
            'click_action' =>'CHALLENGE_DETAIL_SCREEN', 
            'data_id' => $challenge->id, 
        ]);
        // $challenge->user->notify(new ChallengeNotification($challenge->id,$challenge->name));
        $user = $challenge->user;
        $notify_user = User::find($user->id);
        Notifications::send($notify_user, new ChallengeNotification($challenge->id,$challenge->title));
        $challenge->notifications()->save($notification);
        // TO ADMIN
        $notification = new Notification([
            'user_id' => 1, 
            'title' => 'New Challenge Created', 
            'body' => $challenge->user->name ?? $challenge->user->username.' have Created The Challenge '.$challenge->name, 
            'click_action' =>'CHALLENGE_DETAIL_SCREEN', 
            'data_id' => $challenge->id, 
        ]);
        $challenge->notifications()->save($notification);
    }

    /**
     * Handle the challenges "updated" event.
     *
     * @param  \App\challenges  $challenge
     * @return void
     */
    public function updated(Challenge $challenge)
    {
       
    }

    /**
     * Handle the challenges "deleted" event.
     *
     * @param  \App\challenges  $challenge
     * @return void
     */
    public function deleted(Challenge $challenge)
    {
        // TO CHALLENGE OWNER
        $notification = new Notification([
            'user_id' => $challenge->user_id, 
            'title' => 'Challenge Rejected', 
            'body' => 'Your Challenge '.$challenge->name.' has been rejected by admin', 
            'click_action' =>'CHALLENGE_DETAIL_SCREEN', 
            'data_id' => $challenge->id, 
        ]);
        // $challenge->user->notify(new ChallengeNotification($challenge->id,$challenge->name));
        $user = $challenge->user;
        $notify_user = User::find($user->id);
        Notifications::send($notify_user, new ChallengeNotification($challenge->id,$challenge->title));
        $challenge->notifications()->save($notification);
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
