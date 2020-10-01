<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AskCandidate;
use App\Models\Challenge;

class getCandidatesResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:result';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Response Result for Second Vote System';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $challenges = AskCandidate::where('updated_at' , '>=' , now())->latest()->get()->unique('challenge_id');
        $all_chellenges = Challenge::get();
        foreach($all_chellenges as $item){
            if($item->status <> Expired() && $item->acceptedChallenges->first() == null && now() >= $item->after_date){
                    $item->setStatus(Expired());
            }else if($item->status == Approved() && now() >= $item->after_date) {
                $isSubmited = 0;
                $acceptedChallenges = $item->acceptedChallenges;
                foreach ($acceptedChallenges as $acceptedChallenge) {
                    $isSubmited = $acceptedChallenge->submitChallenge ? ++$isSubmited : $isSubmited;
                }
                if($isSubmited > 0){
                    $item->setStatus(ResultPending());
                }
            }
        }
        foreach ($challenges as $value) {
            $challenge = Challenge::findOrFail($value->challenge_id);
            $res = $this->result($challenge);
            $this->info($res['message']);
        }
    }

    public function result(Challenge $challenge) {
        $admin = AskCandidate::where('challenge_id',$challenge->id)
                ->where('vote','admin')->count();
        $premiumUsers = AskCandidate::where('challenge_id',$challenge->id)
                ->where('vote','premiumUsers')->count();
        $result = $admin <=> $premiumUsers;
        if($result == -1){
            $message['message'] = 'Premium Users will decide the winner by vote';
            $challenge->allowVoter = 'premiumUsers';
            $challenge->update();
        } elseif ($result == 0 || $result == 1 ) {
            $message['message'] = config('global.ADMIN_DECIDE_WINNER_MESSAGE');
            $challenge->allowVoter = 'admin';
            $challenge->update();
        }
        return $message;
    }

}
