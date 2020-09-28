<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;

class DonateNotification extends Notification
{
    use Queueable;

    protected $user_name;
    protected $challenge_id;
    protected $challenge_title;
    protected $amount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_name, $challenge_id, $challenge_title = null, $amount)
    {
        $this->user_name = $user_name;
        $this->challenge_id = $challenge_id;
        $this->challenge_title = $challenge_title;
        $this->amount = $amount;
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
        if($this->user_name == 'current_user'){
            $message = new FcmMessage();
            $message->content([
                'title' => 'You have Donated Successfully!',
                'body' => config('global.CURRENCY').$this->amount.' has been debited',
                'sound'        => '', // Optional
                'icon'         => 'favicon.ico', // Optional
                'click_action' =>'CHALLENGE_DETAIL_SCREEN', // Optional
            ])->data([
                'data_id' => $this->challenge_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
        } else {
            $message = new FcmMessage();
            $message->content([
                'title' => ($this->user_name ?? $this->user_name).' have Donated on Your Challenge '.$this->challenge_title,
                'body' => config('global.CURRENCY').$this->amount.' has been donated',
                'sound'        => '', // Optional
                'icon'         => 'favicon.ico', // Optional
                'click_action' =>'CHALLENGE_DETAIL_SCREEN', // Optional
            ])->data([
                'data_id' => $this->challenge_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
        }

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
