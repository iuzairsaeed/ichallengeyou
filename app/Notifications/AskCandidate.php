<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;

class AskCandidate extends Notification
{
    protected $challenge_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($challenge_id)
    {
        $this->challenge_id = $challenge_id;
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
            'title'        => 'Challenge Result',
            'body'         => 'Result has been tied, Do you want to ask the App Admin to Evaluate or The Public?',
            'sound'        => '', // Optional
            'icon'         => 'favicon.ico', // Optional
            'click_action' => 'ASK_RESULT_DIALOG' // Optional
        ])->data([
            'data_id' => $this->challenge_id // Optional
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
