<?php

namespace App\Http\Controllers\Web;

use App\Notifications\ChallengeUpdateNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ChallengeRepository;
use App\Repositories\VoteRepository;
use App\Models\SubmitChallenge;
use App\Models\AcceptedChallenge;
use App\Models\Notification;
use App\Models\Challenge;
use App\Models\Amount;
use App\Models\Bid;
use App\Models\Vote;
use App\Models\User;
use App\Models\Transaction;
use DB;
use Notification as Notifications;

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
            $data = $this->getSubmitors($challenge,$request)->original;

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
            # CHANGE STATUS
            if($request->is_active == 'pending'){
                $challenge->setStatus(Pending());
            } elseif ($request->is_active == 'approved')  {
                $challenge->setStatus(Approved());
            } elseif ($request->is_active == 'denied')  {
                $challenge->setStatus(Denied());
            } elseif ($request->is_active == 'expired')  {
                $challenge->setStatus(Expired());
            }
            # VOTER DECIDE
            if($request->is_voter == 'premiumUsers'){
                $challenge->allowVoter = 'premiumUsers';
                $challenge->update();
            } else {
                $challenge->allowVoter = 'donators';
                $challenge->update();
            }
             # APPROVED CHALLENGE
            if ($challenge->status == 'Approved') {
                $body = 'Congratulation! Your Challenge '.$challenge->title.' has been Approved';
                // TO CHALLENGE OWNER
                $notification = new Notification([
                    'user_id' => $challenge->user_id,
                    'title' => 'Challenge Approved',
                    'body' => $body,
                    'click_action' =>'CHALLENGE_DETAIL_SCREEN',
                    'data_id' => $challenge->id,
                ]);
                $notify_user = User::find($challenge->user->id);
                Notifications::send($notify_user, new ChallengeUpdateNotification($challenge->id,$challenge->title,$body));
                $challenge->notifications()->save($notification);
            }
            # DENIED CHALLENGE
            if ($challenge->status == 'Denied') {
                $creator = $challenge->user;
                (float)$amount = $challenge->amounts->where('type' , 'initial')->first()->amount;
                $creator->balance = $creator->getAttributes()['balance'] + $amount;
                $creator->update();

                $body = 'Your Challenge '.$challenge->title.' has been Rejected by admin';
                // TO CHALLENGE OWNER
                $notification = new Notification([
                    'user_id' => $challenge->user_id,
                    'title' => 'Challenge Rejected',
                    'body' => $body,
                    'click_action' =>'CHALLENGE_DETAIL_SCREEN',
                    'data_id' => $challenge->id,
                ]);
                $notify_user = $challenge->user;
                Notifications::send($notify_user, new ChallengeUpdateNotification($challenge->id,$challenge->title,$body));
                $challenge->notifications()->save($notification);

                $transaction = [
                    'user_id' => $challenge->user->id,
                    'challenge_id' => $challenge->id,
                    'amount' => $amount,
                    'type' => 'refund',
                    'status' => "paid",
                ];
                Transaction::create($transaction);
            }
            return redirect()->back()->with('success', 'Challenge Updated Successfully!');
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function destroy(Challenge $challenge)
    {
        try {
            DB::beginTransaction();
            $challenge->setStatus(Deleted());
            $creator = $challenge->user;
            (float)$amount = $challenge->amounts->where('type' , 'initial')->first()->amount;
            $creator->balance = $creator->getAttributes()['balance'] + $amount;
            $creator->update();
            $this->model->delete($challenge);
            // Notification
            $body = 'Your Challenge '.$challenge->title.' has been Rejected by admin';
            $notify_user = $challenge->user;
            Notifications::send($notify_user, new ChallengeUpdateNotification($challenge->id,$challenge->title,$body));

            $transaction = [
                'user_id' => $challenge->user->id,
                'challenge_id' => $challenge->id,
                'amount' => $amount,
                'type' => 'refund',
                'status' => "paid",
            ];
            Transaction::create($transaction);
            DB::commit();

            return redirect()->back()->with('success', 'Challenge Deleted Successfully');
        } catch (\Throwable $th) {
            DB::rollback();
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
        $withTrash = true;

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
            $item['showTrophy'] = $item->submitChallenge->isWinner ? 'Winner' : '-';
            $item['vote_up'] = $item->submitChallenge->votes->where('vote_up', true)->count();
            $item['vote_down'] = $item->submitChallenge->votes->where('vote_down', true)->count();
            $item['total_votes'] = ($item->submitChallenge->votes->where('vote_up', true)->count() -
                                    $item->submitChallenge->votes->where('vote_down', true)->count());
            $item['serial'] = $serial++;
            return $item;
        });
        return response($data, 200);
    }

}
