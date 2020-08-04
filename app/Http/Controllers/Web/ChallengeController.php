<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChallengeRepository;
use App\Repositories\VoteRepository;
use App\Models\SubmitChallenge;
use App\Models\AcceptedChallenge;
use App\Models\Challenge;
use App\Models\Amount;
use App\Models\Bid;
use App\Models\Vote;

class ChallengeController extends Controller
{
    protected $model;

    public function __construct(Challenge $model)
    {
        $this->model = new ChallengeRepository($model);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request)
    {
        $orderableCols = ['created_at', 'title', 'start_time', 'user.name'];
        $searchableCols = ['title'];
        $whereChecks = [];
        $whereOps = [];
        $whereVals = [];
        $with = ['donations','bids','acceptedChallenges'];
        $withCount = [];
        $currentStatus = [];
        $withSums = ['amounts'];
        $withSumsCol = ['amount'];
        $addWithSums = [];
        $whereHas = null;
        $withTrash = true;

        $data = $this->model->getData($request, $with, $withTrash, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);

        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            $item['amounts_sum'] = config('global.CURRENCY').' '.$item->amounts_sum;
            return $item;
        });
        return response($data, 200);
    }

    

    public function index()
    {
        return view('challenges.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required|min:2'
        // ]);
        // return $this->model->create($request->only($this->model->getModel()->fillable));
    }

    public function show($id,Request $request)
    {
        try {
            $challenge = Challenge::withTrashed()->findOrFail($id);
            $count = 0; $isWinner = 0;
            $data = $this->getSubmitors($challenge,$request)->original;
            $results = $this->result($challenge)->original;
            if($results){
                foreach ($results as $result) {
                    if(($result['result'] >= 40 && $result['result'] <= 60) || $result['result'] >= 51 ){
                        $count++;   
                    }
                }
            }
            if($isWinner <> 1){
                if($count == 1){
                    foreach ($data['data'] as $value) {
                        foreach ($results as $result) {
                            if($value->user_id == $result['id'] && $result['result'] >= 51){
                                $value['isWinner'] = true;
                            }
                        }
                    }
                }
            }
            $winner = optional($data['data'])->where('isWinner','Winner')->first();
            
            return view('challenges.show', compact('challenge','winner'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function edit(Challenge $challenge)
    {
        // return view('challenges.edit', compact('challenge'));
    }

    public function update(Challenge $challenge, Request $request)
    {
        try {
            if($request->is_active == 'pending'){
                $challenge->setStatus(Pending());
            } else {
                $challenge->setStatus(Approved());
            }
            if($request->is_voter == 'premiumUsers'){
                $challenge->allowVoter = 'premiumUsers';
                $challenge->update();
            } else {
                $challenge->allowVoter = 'donators';
                $challenge->update();
            }
            return redirect()->back()->with('success', 'Challenge Updated Successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function destroy(Challenge $challenge)
    {
        try {
            $challenge->setStatus(Deleted());
            $this->model->delete($challenge);
            return redirect()->back()->with('success', 'Challenge Deleted Successfully');
        } catch (\Throwable $th) {
            throw $th;
        } 

    }

    public function restore($id)
    {
        try {
            $challenge = Challenge::withTrashed()->findOrFail($id);
            $challenge->restore();
            $challenge->setStatus(Approved());
            return redirect()->back()->with('success', 'Challenge Restore Successfully');
        } catch (\Throwable $th) {
            throw $th;
        } 

    }

    public function getDonations($id, Request $request)
    {
        $model = new Amount;
        $this->model = new ChallengeRepository($model);
        $orderableCols = ['user_id', 'amount', 'created_at'];
        $searchableCols = [];
        $whereChecks = ['challenge_id', 'type'];
        $whereOps = ['=','='];
        $whereVals = [$id, 'donation'];
        $with = ['user'];
        $withCount = [];
        $currentStatus = [];
        $withSums = [];
        $withSumsCol = [];
        $addWithSums = [];
        $whereHas = null;
        $withTrash = false;

        $data = $this->model->getData($request, $with, $withTrash, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);

        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            return $item;
        });
        return response($data, 200);
    }

    public function getBids($id, Request $request)
    {
        $model = new Bid;
        $this->model = new ChallengeRepository($model);
        $orderableCols = ['user_id', 'bid_amount', 'created_at'];
        $searchableCols = [];
        $whereChecks = ['challenge_id'];
        $whereOps = ['='];
        $whereVals = [$id];
        $with = ['user'];
        $withCount = [];
        $currentStatus = [];
        $withSums = [];
        $withSumsCol = [];
        $addWithSums = [];
        $whereHas = null;
        $withTrash = false;

        $data = $this->model->getData($request, $with, $withTrash, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);

        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            return $item;
        });
        return response($data, 200);
    }

    public function result(Challenge $challenge)
    {
        $vote = new Vote;
        $this->model = new VoteRepository($vote);
        try {
            $acceptedChallenges = $challenge->acceptedChallenges;
            $total_votes = 0;
            foreach ($acceptedChallenges as $value) {
                $submitedChallenge = $value->submitChallenge;
                $total_votes += Vote::where('submited_challenge_id', $submitedChallenge->id)
                ->where('vote_up', true)
                ->count();
            }   
            $data = $this->model->getResult($challenge,$total_votes);
            return response($data,200);
        } catch (\Throwable $th) {
            return response(null,400);
        }
    }

    public function getSubmitors(Challenge $challenge, Request $request)
    {
        $model = new AcceptedChallenge;
        $this->model = new ChallengeRepository($model);

        $orderableCols = ['user_id', 'created_at'];
        $searchableCols = [];
        $whereChecks = ['challenge_id'];
        $whereOps = ['='];
        $whereVals = [$challenge->id];
        $with = ['user','submitFiles'];
        $withCount = [];
        $currentStatus = [];
        $withSums = [];
        $withSumsCol = [];
        $addWithSums = [];
        $whereHas = 'submitChallenge';
        $withTrash = false;

        $data = $this->model->getData($request, $with, $withTrash, $withCount, $whereHas, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);
        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['isWinner'] = $item->submitChallenge->isWinner ? 'Winner' : '-';
            $item['serial'] = $serial++;
            return $item;
        });
        return response($data, 200);
    }
    
}
