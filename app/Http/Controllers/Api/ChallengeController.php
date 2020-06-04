<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Challenges\ChallengeRequest;
use App\Http\Requests\Challenges\CreateChallengeRequest;
use App\Http\Requests\Comments\CreateCommentRequest;
use App\Http\Requests\Donations\CreateDonationRequest;
use App\Http\Resources\ChallengeCollection;
use App\Http\Resources\ChallengeList;
use App\Http\Resources\ChallengeDetailCollection;
use App\Repositories\ChallengeRepository;
use App\Models\Challenge;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Amount;
use App\Models\User;
use Carbon\Carbon;
use DB;

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
    public function index(Request $request)
    {
        $orderableCols = ['created_at', 'title', 'start_time', 'user.name', 'trend', 'amounts_sum', 'amounts_trend_sum'];
        $searchableCols = ['title'];
        $whereChecks = [];
        $whereOps = [];
        $whereVals = [];
        $with = [];
        $withCount = [];
        $currentStatus = [Approved()];
        $withSums = ['amounts'];
        $withSumsCol = ['amount'];
        $addWithSums = ['trend'];

        $data = $this->model->getData($request, $with, $withCount, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);
        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            $item['amounts_sum'] = config('global.CURRENCY').$item->amounts_sum;
            $item['like'] = $item->userReaction->like ?? false;
            $item['unlike'] = $item->userReaction->unlike ?? false;
            $item['favorite'] = $item->userReaction->favorite ?? false;
            return $item;
        });
        $data['data'] = ChallengeCollection::collection($data['data']);
        return response($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateChallengeRequest $request)
    {
        try {
            $data = $request->all();

            if($request->hasFile('file')){
                $data['file'] = uploadFile($request->file, challengesPath(), null);
            }
            $data['user_id'] = auth()->id();
            $data['start_time'] = Carbon::createFromFormat('m-d-Y h:m A', $request->start_time)->toDateTimeString();

            $challenge = $this->model->create($data);
            $challenge->setStatus(Pending());
            return response(['message' => 'Challenge has been created.'], 200);
        } catch (\Exception  $e) {
            throw $e;
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function show(Challenge $challenge, Request $request)
    {
        $user_id = $request->id;
        $challenge_id = $challenge->id;
        $whereChecks = ['id'];
        $whereOps = ['='];
        $whereVals = [$challenge->id];
        $with = array(
            'userReaction' => function($query) use ($user_id, $challenge_id) {
                $query->where('user_id', $user_id)->where('challenge_id', $challenge_id); 
            },
            'donations' => function($query) {
                $query->with('user'); 
            },
            'initialAmount'            
        );
        $withCount = [];
        $withSums = ['amounts'];
        $withSumsCol = ['amount'];
        $addWithSums = [];
        $user_id = [$request->id];
        $challenge_id = [$challenge->id];

        $data = $this->model->showChallenge($request,$user_id,$challenge_id,$with,$withSums, $withSumsCol,$withCount,$whereChecks, $whereOps, $whereVals);

        if($challenge->user_id == $request->id){
            collect($data['data'])->map(function ($item) use (&$serial) {
                $item['amounts_sum'] = config('global.CURRENCY').$item->amounts_sum;
                $item['acceptBtn'] = false;
                $item['donateBtn'] = false;
                $item['editBtn'] = true;
            });
        } elseif ($challenge->user_id != $request->id) {
            collect($data['data'])->map(function ($item) use (&$serial) {
                $item['amounts_sum'] = config('global.CURRENCY').$item->amounts_sum;
                $item['acceptBtn'] = true;
                $item['donateBtn'] = true;
                $item['editBtn'] = false;
            });
        }
        // $data['data'] = ChallengeDetailCollection::collection($data['data']);
        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function edit(Challenge $challenge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function update(ChallengeRequest $request, Challenge $challenge)
    {
        
        try {
            $data = $request->all();

            if($request->hasFile('file')){
                $data['file'] = uploadFile($request->file, challengesPath(), $challenge->file);
            }
            $data['user_id'] = auth()->id();
            $data['start_time'] = Carbon::createFromFormat('m-d-Y h:m A', $request->start_time)->toDateTimeString();
            $challenge = $this->model->update($data , $challenge );
            return response(['message' => 'Challenge has been updated.'], 200);

        } catch (\Throwable $th) {
            throw $th;
        }
        
        $this->model->update($request->only($this->model->getModel()->fillable), $challenge);
        return $this->model->find($challenge->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Challenge $challenge)
    {
        return $this->model->delete($challenge);
    }

    /**
     * Donate on the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function donation(Challenge $challenge, CreateDonationRequest $request)
    {
        $user = auth()->user();
        if($request->amount > $user->balance){
            return response(['message' => 'Donation amount cannot be greater than current account balance.'], 200);
        }
        $donation = new Amount([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 'donation'
        ]);
        $challenge->amounts()->save($donation);
        $challenge->increment('trend');
        $user->balance -= $request->amount;
        $user->update();
        return response(['message' => 'Donation has been submitted.'], 200);
    }

    /**
     * Get Comments of the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function comments(Challenge $challenge)
    {
        $comments = $challenge->comments;
        $data = [
            'message' => $comments->count() === 0 ? 'No comments found.' : 'Success',
            'data' => $comments,
        ];
        return response($data, 200);
    }

    /**
     * Comment on the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function comment(Challenge $challenge, CreateCommentRequest $request)
    {
        $comment = new Comment([
            'user_id' => auth()->id(),
            'text' => $request->text
        ]);
        $challenge->comments()->save($comment);
        return response(['message' => 'Comment has been submitted.'], 200);
    }

    /**
     * Like the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function like(Challenge $challenge)
    {
        $reaction = $challenge->userReaction;
        if(!$reaction){
            $reaction = new Reaction([
                'user_id' => auth()->id(),
                'like' => true,
            ]);
            $challenge->userReaction()->save($reaction);
            $challenge->increment('trend');
        }else{
            $reaction->update([
                'like' => $reaction->like ? false : true,
                'unlike' => false
            ]);
            $challenge->decrement('trend');
        }
        return response(['message' => 'Reaction Updated!'], 200);
    }

    /**
     * Unlike the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function unlike(Challenge $challenge)
    {
        $reaction = $challenge->userReaction;
        if(!$reaction){
            $reaction = new Reaction([
                'user_id' => auth()->id(),
                'unlike' => true,
            ]);
            $challenge->userReaction()->save($reaction);
        }else{
            $reaction->update([
                'like' => false,
                'unlike' => $reaction->unlike ? false : true
            ]);
        }
        return response(['message' => 'Reaction Updated!'], 200);
    }

    /**
     * Favorite the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function favorite(Challenge $challenge)
    {
        $reaction = $challenge->userReaction;
        if(!$reaction){
            $reaction = new Reaction([
                'user_id' => auth()->id(),
                'favorite' => true,
            ]);
            $challenge->userReaction()->save($reaction);
        }else{
            $reaction->update([
                'favorite' => $reaction->favorite ? false : true
            ]);
        }
        return response(['message' => 'Challenge Added to Favorites!'], 200);
    }

    public function myList(Request $request)
    {
        $orderableCols = ['created_at', 'title', 'start_time', 'user.name', 'trend', 'amounts_sum', 'amounts_trend_sum'];
        $searchableCols = ['title'];
        $whereChecks = ['user_id'];
        $whereOps = ['='];
        $whereVals = [auth()->id()];
        $with = [];
        $withCount = [];
        $currentStatus = [Approved()];
        $withSums = ['amounts'];
        $withSumsCol = ['amount'];
        $addWithSums = ['trend'];

        $data = $this->model->getData($request, $with, $withCount, $withSums, $withSumsCol, $addWithSums, $whereChecks,
                                        $whereOps, $whereVals, $searchableCols, $orderableCols, $currentStatus);

        $data['data'] = ChallengeList::collection($data['data']);
        return response($data, 200);

    }

}
