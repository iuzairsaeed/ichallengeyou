<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DonateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $to;
    protected $challenge_id;
    protected $challenge_title;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($to, $challenge_id, $challenge_title = null)
    {
        $this->to = $to;
        $this->challenge_id = $challenge_id;
        $this->challenge_title = $challenge_title;
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
        if($this->to === 'current_user'){
            $message = new FcmMessage();
            $message->content([
                'title' => 'Withdrawal Transaction',
                'body' => $this->amount.' has been debited', 
                'sound'        => '', // Optional 
                'icon'         => 'favicon.ico', // Optional 
                'click_action' =>'CHALLENGE_DETAIL_SCREEN', // Optional 
            ])->data([
                'data_id' => $this->challenge_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
        } else {
            $message = new FcmMessage();
            $message->content([
                'title' => auth()->user()->name.' have Donated on Your Challenge '.$this->challenge_title,
                'body' => $this->amount.' has been debited', 
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