<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;

class CommentNotification extends Notification
{
    use Queueable;

    protected $comment;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment) {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['fcm'];
    }

   /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return  Benwilkins\FCM\FcmMessage;
     */
    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $message->content([
            'title' => 'You got comments on your Challenge',
            'body' => ($this->comment->user->name ?? $this->user->username).' has been Commented on the Challenge '.$this->comment->challenge->title,
            'sound'        => '', // Optional
            'icon'         => 'favicon.ico', // Optional
            'click_action' => 'CHALLENGE_DETAIL_SCREEN', // Optional
        ])->data([
            'data_id' => $this->comment->challenge_id, // Optional
        ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
