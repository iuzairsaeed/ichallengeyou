<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;

class ChallengeSubmited extends Notification
{
    protected $action;
    protected $data_id;
    protected $winner;
    protected $creater;
    protected $challenge;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($action, $data_id, $winner, $creater, $challenge)
    {
        $this->action = $action;
        $this->data_id = $data_id;
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
        if($this->action == 'onCreated'){
            $message = new FcmMessage();
            $message->content([
                'title' => 'Challenge Submited',
                'body' => ($this->winner->name ?? $this->winner->username) .' have Submited the Challenge '.$this->challenge->title,
                'sound'        => '', // Optional
                'icon'         => 'favicon.ico', // Optional
                'click_action' => 'SUBMITED_CHALLENGE_LIST_SCREEN' // Optional
            ])->data([
                'data_id' => $this->data_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
        }
        if($this->action == 'onUser'){
            $message = new FcmMessage();
            $message->content([
                'title' => 'Challenge Submited',
                'body' => 'You have Submited the Challenge '.$this->challenge->title,
                'sound'        => '', // Optional
                'icon'         => 'favicon.ico', // Optional
                'click_action' => 'SUBMITED_CHALLENGE_LIST_SCREEN' // Optional
            ])->data([
                'data_id' => $this->data_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
        }

        if($this->action == 'toDonator&Creator'){
            $message = new FcmMessage();
            $message->content([
                'title' => 'Win Challenge',
                'body' => ($this->winner->name ?? $this->winner->username).' WIN the Challenge '.$this->challenge->title,
                'sound'        => '', // Optional
                'icon'         => 'favicon.ico', // Optional
                'click_action' => 'SUBMITED_CHALLENGE_LIST_SCREEN' // Optional
            ])->data([
                'data_id' => $this->data_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        } elseif ($this->action == 'toSubmitor') {
            $message = new FcmMessage();
            $message->content([
                'title' => 'Win Challenge',
                'body' => ($this->winner->name ?? $this->winner->username).' WIN the Challenge '.$this->challenge->title,
                'sound'        => '', // Optional
                'icon'         => 'favicon.ico', // Optional
                'click_action' => 'SUBMITED_CHALLENGE_LIST_SCREEN' // Optional
            ])->data([
                'data_id' => $this->data_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.

        } elseif ($this->action == 'toWinner') {
            $message = new FcmMessage();
            $message->content([
                'title' => 'Congratulations!! You have Won The Challenge',
                'body' => ($this->winner->name ?? $this->winner->username).' WIN the Challenge '.$this->challenge->title,
                'sound'        => '', // Optional
                'icon'         => 'favicon.ico', // Optional
                'click_action' => 'SUBMITED_CHALLENGE_LIST_SCREEN' // Optional
            ])->data([
                'data_id' => $this->data_id // Optional
            ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
        }

        return $message;
    }



    /**
     * Get the array representation of the notification.
     *
     * @par`am`  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
