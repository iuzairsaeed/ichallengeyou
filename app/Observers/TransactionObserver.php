<?php

namespace App\Observers;
use App\Models\Transaction;
use App\Models\Notification;
use App\Notifications\LoadNotification;
use App\Notifications\MiscellaneousNotification;
use App\Notifications\WithdrawalNotification;
use App\Notifications\DonateNotification;


class TransactionObserver
{
    /**
     * Handle the submit challenge "created" event.
     *
     * @param  \App\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        $user = auth()->user() ;
        $transaction_type = $transaction->type;
        switch ($transaction_type) {
            case 'load':
                // TO USER
                Notification::create([
                    'user_id' => $transaction->user_id,
                    'title' => 'Balance Loaded Successfully!', 
                    'body' => config('global.CURRENCY').' '.$transaction->amount.' has been Successfully Added to your Account!', 
                    'click_action' =>'HOME_SCREEN', 
                    'data_id' => $transaction->user_id, 
                ]);
                $transaction->user->notify(new LoadNotification($transaction->amount));
                // TO ADMIN
                Notification::create([
                    'user_id' => 1,
                    'title' => 'Load Balance', 
                    'body' => $transaction->user->name.'has Loaded '.config('global.CURRENCY').' '.$transaction->amount.' Balance Successfully!', 
                    'click_action' =>'HOME_SCREEN', 
                    'data_id' => $transaction->user_id, 
                ]);
                break;
            case 'miscellaneous':
                // TO USER
                Notification::create([
                    'user_id' => $transaction->user_id, 
                    'title' => 'Congratulation! ♥', 
                    'body' => 'By using '.config('global.CURRENCY').' '.config('global.PREMIUM_COST').' You\'re Premium User Now!', 
                    'click_action' =>'HOME_SCREEN', 
                    'data_id' => $transaction->user_id, 
                ]);
                $transaction->user->notify(new MiscellaneousNotification);
                // TO ADMIN
                Notification::create([
                    'user_id' => 1,
                    'title' => 'Miscellaneous Amount', 
                    'body' => 'By using '.config('global.CURRENCY').' '.config('global.PREMIUM_COST').' '.$transaction->user->name.' is Premium User Now!', 
                    'click_action' =>'HOME_SCREEN', 
                    'data_id' => $transaction->user_id, 
                ]);
                break;
            case 'withdraw':
                // TO USER
                Notification::create([
                    'user_id' => $transaction->user_id,
                    'title' => 'Withdrawal Transaction',
                    'body' => config('global.CURRENCY').' '.$transaction->amount.' has been debited', 
                    'click_action' =>'HOME_SCREEN', 
                    'data_id' => $transaction->user_id, 
                ]);
                $transaction->user->notify(new WithdrawalNotification($transaction->amount));
                // TO ADMIN
                Notification::create([
                    'user_id' => 1,
                    'title' => 'Withdrawal Transaction',
                    'body' => $transaction->user->name.' has been debited '.config('global.CURRENCY').' '.$transaction->amount, 
                    'click_action' =>'HOME_SCREEN', 
                    'data_id' => $transaction->user_id, 
                ]);
                break;
            case 'donate':
                // TO USER
                Notification::create([
                    'user_id' => auth()->id() ?? 2,
                    'title' => 'You have Donated Successfully!',
                    'body' => config('global.CURRENCY').' '.$transaction->amount.' has been donated', 
                    'click_action' =>'CHALLENGE_DETAIL_SCREEN', 
                    'data_id' => $transaction->challenge_id, 
                ]);
                // TO Challenge Owner
                $transaction->user->notify(new DonateNotification('current_user',$transaction->challenge_id,$transaction->challenge->title,$transaction->amount));
                Notification::create([
                    'user_id' => $transaction->challenge->user->id,
                    'title' => (auth()->user()->name ?? 'Seeder Test User' ).' have Donated on Your Challenge '.$transaction->challenge->title,
                    'body' => config('global.CURRENCY').' '.$transaction->amount.' has been donated', 
                    'click_action' =>'CHALLENGE_DETAIL_SCREEN', 
                    'data_id' => $transaction->challenge_id, 
                ]);
                $transaction->challenge->user->notify(new DonateNotification('creater',$transaction->challenge_id,$transaction->challenge->title,$transaction->amount));
                // TO ADMIN
                Notification::create([
                    'user_id' => 1,
                    'title' => ($transaction->user->name ?? 'Seeder Test User' ).' have Donated on Challenge '.$transaction->challenge->title,
                    'body' => config('global.CURRENCY').' '.$transaction->amount.' has been donated', 
                    'click_action' =>'CHALLENGE_DETAIL_SCREEN', 
                    'data_id' => $transaction->challenge_id, 
                ]);
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * Handle the submit challenge "updated" event.
     *
     * @param  \App\Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction)
    {
        
    }

    /**
     * Handle the submit challenge "deleted" event.
     *
     * @param  \App\Transaction  $transaction
     * @return void
     */
    public function deleted(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the submit challenge "restored" event.
     *
     * @param  \App\Transaction  $transaction
     * @return void
     */
    public function restored(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the submit challenge "force deleted" event.
     *
     * @param  \App\Transaction  $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction)
    {
        //
    }
}
