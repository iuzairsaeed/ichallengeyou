<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Challenges\CreateChallengeRequest;
use App\Http\Requests\Comments\CreateCommentRequest;
use App\Http\Requests\Donations\CreateDonationRequest;
use App\Repositories\ChallengeRepository;
use App\Models\Challenge;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Amount;
use Carbon\Carbon;

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
        $with = ['user'];
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
            $item['like'] = $item->userReaction->like ?? 0;
            $item['unlike'] = $item->userReaction->unlike ?? 0;
            $item['favorite'] = $item->userReaction->favorite ?? 0;
            return $item;
        });
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
        $data = $request->all();

        if($request->hasFile('file')){
            $data['file'] = uploadFile($request->file, challengesPath(), null);
        }
        $data['user_id'] = auth()->id();
        $data['start_time'] = Carbon::createFromFormat('m-d-Y h:m A', $request->start_time)->toDateTimeString();

        $this->model->create($data);
        $this->model->setStatus(Pending());
        return response(['message' => 'Challenge has been created.'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Challenge $challenge
     * @return \Illuminate\Http\Response
     */
    public function show(Challenge $challenge)
    {
        $challenge['like'] = $challenge->userReaction->like ?? 0;
        $challenge['unlike'] = $challenge->userReaction->unlike ?? 0;
        $challenge['favorite'] = $challenge->userReaction->favorite ?? 0;
        $data = [
            'data' => $challenge,
        ];
        return response($data, 200);
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
    public function update(Request $request, Challenge $challenge)
    {
        $this->validate($request, [
            'name' => 'required|min:2'
        ]);
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
}
