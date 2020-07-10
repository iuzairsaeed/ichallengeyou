<?php

namespace App\Observers;
use Illuminate\Http\Request;
use App\Models\Amount;

class AmountObserver
{
    /**
     * Handle the submit challenge "created" event.
     *
     * @param  \App\Amount  $Amount
     * @return void
     */
    public function created(Amount $amount)
    {
        try {
            dd($amount);
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
