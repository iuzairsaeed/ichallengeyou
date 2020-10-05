<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\AskCandidate;
use App\Models\Challenge;
use App\Models\SubmitChallenge;
use App\Http\Controllers\Api\SubmitChallengeController;
use DB;

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
        $challenges = DB::select('SELECT 
        c.id
        FROM challenges c
        INNER JOIN accepted_challenges AS ac ON ac.challenge_id = c.id
        INNER JOIN submit_challenges AS sc ON ac.id = sc.accepted_challenge_id
        INNER JOIN statuses s ON s.model_id = c.id 
        WHERE
        date_add(date_add(DATE_ADD(start_time, INTERVAL duration_minutes MINUTE), INTERVAL duration_hours HOUR), INTERVAL duration_days DAY) < CURRENT_TIMESTAMP()
        AND s.id IN (
            SELECT max(id) FROM `statuses` WHERE model_type = "App\\\Models\\\Challenge"
        GROUP BY model_id)
        AND s.name = "Approved" 
        GROUP BY c.id'
        );

        $expired_challenges = DB::select('SELECT id from challenges
        where 
        date_add(date_add(DATE_ADD(start_time, INTERVAL duration_minutes MINUTE), INTERVAL duration_hours HOUR), INTERVAL duration_days DAY) < CURRENT_TIMESTAMP()
        and id not in (
        SELECT 
        c.id
        from challenges c
        INNER JOIN accepted_challenges as ac on ac.challenge_id = c.id
        INNER JOIN submit_challenges as sc on ac.id = sc.accepted_challenge_id
        INNER JOIN statuses s on s.model_id = c.id 
        WHERE
        date_add(date_add(DATE_ADD(start_time, INTERVAL duration_minutes MINUTE), INTERVAL duration_hours HOUR), INTERVAL duration_days DAY) < CURRENT_TIMESTAMP()
        and s.id in (
            SELECT max(id) FROM `statuses` WHERE model_type = "App\\\Models\\\Challenge"
        GROUP by model_id)
        and s.name = "Approved"
        and c.deleted_at == null
        GROUP by c.id)'
        );
        if($expired_challenges){
            foreach ($expired_challenges as $expired_challenge) {
                $challenge = Challenge::find($expired_challenge->id);
                if($challenge->status <> Expired() ){
                    $challenge->setStatus(Expired());
                }
            }
        }
        foreach ($challenges as $challenge) {
            $result = DB::select('SELECT 
            submited_challenge_id, sum(vote_up) - sum(vote_down) votescount from votes v 
            inner JOIN submit_challenges sc on sc.id = v.submited_challenge_id 
            inner JOIN accepted_challenges ac on ac.id = sc.accepted_challenge_id 
            where ac.challenge_id = '.$challenge->id.'
            group by submited_challenge_id ORDER by votescount DESC LIMIT 2');
            if(!$result){
                $challenge = Challenge::find($challenge->id);
                $challenge->setStatus(ResultPending());
                DB::update('UPDATE challenges SET allowVoter = "admin" where id = ?', [$challenge->id]);
            }elseif(count($result) == 1  && $result[0]->votescount > 0 ){
                $sub_challenge = SubmitChallenge::find($result[0]->submited_challenge_id);
                $sub_challenge->isWinner = true;
                $sub_challenge->save();
            } elseif ($result[0]->votescount > $result[1]->votescount){
                $sub_challenge = SubmitChallenge::find($result[0]->submited_challenge_id);
                $sub_challenge->isWinner = true;
                $sub_challenge->save();
            } elseif ($result[0]->votescount == $result[1]->votescount) {
                $challenge = Challenge::find($challenge->id);
                $challenge->setStatus(ResultPending());
                DB::update('UPDATE challenges SET allowVoter = "admin" where id = ?', [$challenge->id]);
            } else {
                $challenge = Challenge::find($challenge->id);
                $challenge->setStatus(ResultPending());
                DB::update('UPDATE challenges SET allowVoter = "admin" where id = ?', [$challenge->id]);
            }
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
