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
    protected $signature = 'result:get';

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
        dd($this->result(1));
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
            $message['message'] = 'Admin will decide the Winner';
            $challenge->allowVoter = 'admin';
            $challenge->update();
        }
        return $message;
    }

}
