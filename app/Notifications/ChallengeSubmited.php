<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;

class ChallengeSubmited extends Notification implements ShouldQueue
{
    use Queueable;

    protected $action;
    protected $donator;
    protected $winner;
    protected $creater;
    protected $challenge;
    protected $data_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($action, $data_id, $donator=null, $winner=null, $creater=null, $challenge=null)
    {
        $this->action = $action;
        $this->data_id = $data_id;
        $this->donator = $donator;
        $this->winner = $winner;
        $this->creater = $creater;
        $this->challenge = $challenge;
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
        if($this->action === 'onCreated'){
            $message = new FcmMessage();
            $message->content([
                'title' => 'Challengse Submited', 
                'body' => $this->winner->name.' has been Submited the Challenge '.$this->challenge->title,
                'sound'        => '', // Optional 
                'icon'         => 'favicon.ico', // Optional
                'click_action' => 'CHALLENGE_DETAIL_SCREEN' // Optional
            ])->data([
                'data_id' => $this->data_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        }
        
        if($this->action === 'toDonator&Creator'){
            $message = new FcmMessage();
            $message->content([
                'title' => 'Challengse Submited', 
                'body' => $this->winner->name.' WIN the Challenge '.$this->challenge->title,
                'sound'        => '', // Optional 
                'icon'         => 'favicon.ico', // Optional
                'click_action' => 'CHALLENGE_DETAIL_SCREEN' // Optional
            ])->data([
                'data_id' => $this->data_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        } elseif ($action === 'toSubmitor') {
            $message = new FcmMessage();
            $message->content([
                'title' => 'Challengse Submited', 
                'body' => $this->winner->name.' WIN the Challenge '.$this->challenge->title,
                'sound'        => '', // Optional 
                'icon'         => 'favicon.ico', // Optional
                'click_action' => 'CHALLENGE_DETAIL_SCREEN' // Optional
            ])->data([
                'data_id' => $this->data_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        } elseif ($action === 'toWinner') {
            $message = new FcmMessage();
            $message->content([
                'title' => 'Congratulations!! You have Won The Challenge', 
                'body' => $this->winner->name.' WIN the Challenge '.$this->challenge->title,
                'sound'        => '', // Optional 
                'icon'         => 'favicon.ico', // Optional
                'click_action' => 'CHALLENGE_DETAIL_SCREEN' // Optional
            ])->data([
                'data_id' => $this->data_id // Optional
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
