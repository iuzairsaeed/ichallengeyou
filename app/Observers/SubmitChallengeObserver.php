<?php

namespace App\Observers;

use Illuminate\Http\Request;
use App\Models\SubmitChallenge;
use App\Models\Challenge;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\ChallengeSubmited;
use Notification as Notifications ;

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
            $donators = $submitChallenge->acceptedChallenge->challenge->donations;
            $submitor = $submitChallenge->acceptedChallenge->user;
            $creater = $submitChallenge->acceptedChallenge->challenge->user;
            $challenge = $submitChallenge->acceptedChallenge->challenge;
            // TO CURRENT USER Notification
            $userNotification = new Notification([
                'user_id' => auth()->id(),
                'title' => 'Challenge Submited',
                'body' => 'You have Submited the Challenge '.$challenge->title,
                'click_action' =>'SUBMITED_CHALLENGE_DETAIL_SCREEN',
                'data_id' => $submitChallenge->accepted_challenge_id,
            ]);
            $submitChallenge->notifications()->save($userNotification);
            Notifications::send(auth()->user(), new ChallengeSubmited('onUser',$submitChallenge->accepted_challenge_id, $submitor, $creater, $challenge));

            // Donators Notification
            if($donators->isNotEmpty()){
                foreach ($donators as $donator) {
                    $notification[] = new Notification([
                        'user_id' => $donator->user->id,
                        'title' => 'Challenge Submited',
                        'body' => ($submitor->name ?? $submitor->username).' have Submited the Challenge '.$challenge->title,
                        'click_action' =>'SUBMITED_CHALLENGE_DETAIL_SCREEN',
                        'data_id' => $submitChallenge->accepted_challenge_id,
                    ]);
                    $notify_user = User::find($donator->user->id);
                    Notifications::send( $notify_user, new ChallengeSubmited('onCreated',$submitChallenge->accepted_challenge_id, $submitor, $creater, $challenge));
                }
                $submitChallenge->notifications()->saveMany($notification);
            }
            // Creater Notification
            $createrNotification = new Notification([
                'user_id' => $creater->id,
                'title' => 'Challenge Submited',
                'body' => ($submitor->name ?? $submitor->username).' have Submited the Challenge '.$challenge->title,
                'click_action' =>'SUBMITED_CHALLENGE_DETAIL_SCREEN',
                'data_id' => $submitChallenge->accepted_challenge_id,
            ]);
            $submitChallenge->notifications()->save($createrNotification);
            $notify_user = User::find($creater->id);
            Notifications::send($notify_user, new ChallengeSubmited('onCreated',$submitChallenge->accepted_challenge_id, $submitor, $creater, $challenge));

            // Admin Notification
            $createrNotification = new Notification([
                'user_id' => 1,
                'title' => 'Challenge Submited',
                'body' => ($submitor->name ?? $submitor->username).' have Submited the Challenge '.$challenge->title,
                'click_action' =>'SUBMITED_CHALLENGE_DETAIL_SCREEN',
                'data_id' => $submitChallenge->accepted_challenge_id,
            ]);
            $submitChallenge->notifications()->save($createrNotification);

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
        if($submitChallenge->isWinner == true){
            $donators = $submitChallenge->acceptedChallenge->challenge->donations;
            $winner = $submitChallenge->acceptedChallenge->user;
            $creater = $submitChallenge->acceptedChallenge->challenge->user;
            $challenge = $submitChallenge->acceptedChallenge->challenge;
            $submitors =  $submitChallenge->acceptedChallenge->challenge->acceptedChallenges;

            $a= $challenge->setStatus(Completed());
            // Give Winner Amount of doing challenge
            (float) $amount_sum = $challenge->amount_sum;
            (float) $creater_amount = $amount_sum * ((int)config('global.CREATER_AMOUNT_IN_PERCENTAGE') / 100 );
            (float) $winner_amount = $amount_sum * ((int)config('global.WINNING_AMOUNT_IN_PERCENTAGE') / 100 );
            $winner_amount = round($winner_amount,2);
            $winner->balance = (float)$winner->getRawOriginal('balance') + $winner_amount;
            $winner->save();
            $creater->balance = (float)$creater->getRawOriginal('balance') + $creater_amount;
            $creater->save();

            # TRANSACTION FOR WINNER
            $transaction = new Transaction([
                'user_id' => $winner->id,
                'challenge_id' => $challenge->id,
                'amount' => $winner_amount,
                'type' => 'won_challenge',
                'invoice_id' => null,
                'status' => 'paid',
            ]);
            $winner->transactions()->save($transaction);
            # TRANSACTION FOR Creater
            $transaction = new Transaction([
                'user_id' => $creater->id,
                'challenge_id' => $challenge->id,
                'amount' => $creater_amount,
                'type' => 'won_challenge',
                'invoice_id' => null,
                'status' => 'paid',
            ]);
            $creater->transactions()->save($transaction);
            // TO DONATORS --
            if($donators->isNotEmpty()){
                foreach ($donators as $donator) {
                    $notification[] = new Notification([
                        'user_id' => $donator->user->id,
                        'title' => 'Win Challenge',
                        'body' => $winner->name.' WIN the Challenge '.$challenge->title,
                        'click_action' =>'SUBMITED_CHALLENGE_LIST_SCREEN',
                        'data_id' => $challenge->id,
                    ]);
                    
                    $notify_user = User::find($donator->user->id);
                    Notifications::send($notify_user, new ChallengeSubmited('toDonator&Creator', $challenge->id, $winner, $creater, $challenge));
    
                }
                $submitChallenge->notifications()->saveMany($notification);
            }
            // TO CREATER --
            $createrNotification = new Notification([
                'user_id' => $creater->id,
                'title' => 'Win Challenge',
                'body' => ($winner->name ?? $winner->username).' WIN the Challenge '.$challenge->title,
                'click_action' =>'SUBMITED_CHALLENGE_LIST_SCREEN',
                'data_id' => $challenge->id,
            ]);
            $submitChallenge->notifications()->save($createrNotification);
            $notify_user = User::find($creater->id);
            Notifications::send($notify_user, new ChallengeSubmited('toDonator&Creator', $challenge->id, $winner, $creater, $challenge));

            // TO SUBMItORS
            $submitorNotification = [];
            foreach ($submitors as $submitor) {
                if($winner->id != $submitor->user_id){
                    $submitorNotification[] = new Notification([
                        'user_id' => $submitor->user_id,
                        'title' => 'Win Challenge',
                        'body' => ($winner->name ?? $winner->username).' WIN the Challenge A '.$challenge->title,
                        'click_action' =>'SUBMITED_CHALLENGE_LIST_SCREEN',
                        'data_id' => $challenge->id,
                    ]);
                    $notify_user = User::find($submitor->id);
                    Notifications::send($notify_user, new ChallengeSubmited('toSubmitor', $challenge->id, $winner, $creater, $challenge));

                }
            }
            if($submitorNotification != null){
                $submitChallenge->notifications()->saveMany($submitorNotification);
            }
            // TO WINNER
            $winnerNotification = new Notification([
                'user_id' => $winner->id,
                'title' => 'Yoy WIN the Challenge '.$challenge->title,
                'body' => 'Congratulations! You have Won The Challenge ★',
                'click_action' =>'SUBMITED_CHALLENGE_LIST_SCREEN',
                'data_id' => $challenge->id,
            ]);
            $submitChallenge->notifications()->save($winnerNotification);
            $notify_user = User::find($winner->id);
            Notifications::send($notify_user, new ChallengeSubmited('toWinner', $challenge->id, $winner, $creater, $challenge));

            // TO ADMIN
            Notification::create([
                'user_id' => 1,
                'title' => ($winner->name ?? $winner->username).' - THE WINNER ★',
                'body' => ($winner->name ?? $winner->username).' WIN the Challenge '.$challenge->title,
                'click_action' =>'CHALLENGE_DETAIL_SCREEN',
                'data_id' =>  $challenge->id,
            ]);
        }
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
