<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\Challenge;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\CommentNotification;
use Notification as Notifications;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        // TO USER
        $userNotification = new Notification([
            'user_id' => $comment->challenge->user_id,
            'title' => 'You got comments on your Challenge', 
            'body' => $comment->user->name ?? $comment->user->username.' has been Commented on the Challenge '.$comment->challenge->title, 
            'click_action' =>'CHALLENGE_DETAIL_SCREEN', 
            'data_id' => $comment->challenge_id, 
        ]);
        $comment->notifications()->save($userNotification);
        // $comment->challenge->user->notify(new CommentNotification($comment));
        $user = $comment->challenge->user;
        $notify_user = User::find($user->id);
        Notifications::send($notify_user, new CommentNotification($comment));

        // TO ADMIN
        $userNotification = new Notification([
            'user_id' => 1,
            'title' => 'Comment on Challenge', 
            'body' => $comment->user->name ?? $comment->user->username.' has been Commented on the Challenge '.$comment->challenge->title, 
            'click_action' =>'CHALLENGE_DETAIL_SCREEN', 
            'data_id' => $comment->challenge_id, 
        ]);
        $comment->notifications()->save($userNotification);
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "restored" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "force deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        //
    }
}
