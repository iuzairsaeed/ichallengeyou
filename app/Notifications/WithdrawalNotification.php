<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;

class WithdrawalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $amount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($amount)
    {
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
        $message = new FcmMessage();
        $message->content([
            'title' => 'Withdrawal Transaction',
            'body' => '$'.$this->amount.' has been debited', 
            'sound'        => '', // Optional 
            'icon'         => 'favicon.ico', // Optional
            'click_action' =>'HOME_SCREEN', // Optional
        ])->data([
            'data_id' => auth()->id() // Optional
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
