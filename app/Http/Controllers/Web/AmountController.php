<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Models\Transaction;

class AmountController extends Controller
{

    protected $model;

    public function __construct(Transaction $model)
    {
        $this->model = new Repository($model);
    }

    public function getList(Request $request)
    {
        // dd($request->all());
        $orderableCols = ['user_id','challenge_id','amount','type','created_at'];
        $searchableCols = ['type','amount'];
        $whereChecks = [];
        $whereOps = [];
        $whereVals = [];
        $with = ['challenge','user'];
        $withCount = [];

        $data = $this->model->getData($request, $with, $withCount, $whereChecks, $whereOps, $whereVals, $searchableCols, $orderableCols);
        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            $item['amount'] = config('global.CURRENCY').' '.$item->amount;
            $item['challenge_title'] = $item->challenge->title ?? '';
            switch ($item->type) {
                case 'load':
                    $item['reason'] = 'Load Balance';
                    break;
                case 'won_challenge':
                    $item['reason'] = 'Won Challange';
                    break;
                case 'withdraw':
                    $item['reason'] = 'Withdraw Balance';
                    break;
                case 'donate':
                    $item['reason'] = 'Donate on Challenge';
                    break;
                case 'create_challenge':
                    $item['reason'] = 'Created Challenge';
                    break;
                case 'miscellaneous':
                    $item['reason'] = 'Premium Cost';
                    break;
            }
            $item['type'] = ($item->type == 'load' || $item->type == 'won_challenge') ? 1 : 0;
        });
        return response($data, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('amounts.index');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $amount)
    {
        $amount['amount'] = config('global.CURRENCY').' '.$amount->amount;
        $amount['challenge_title'] = $amount->challenge->title ?? '';
        switch ($amount->type) {
            case 'load':
                $amount['reason'] = 'Load Balance';
                break;
            case 'won_challenge':
                $amount['reason'] = 'Won Challange';
                break;
            case 'withdraw':
                $amount['reason'] = 'Withdraw Balance';
                break;
            case 'donate':
                $amount['reason'] = 'Donate on Challenge';
                break;
            case 'create_challenge':
                $amount['reason'] = 'Created Challenge';
                break;
            case 'miscellaneous':
                $amount['reason'] = 'Premium Cost';
                break;
        }
        return view('amounts.show', compact('amount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $amount)
    {
        try {
            $amount->delete();
            return redirect('/amounts')->with('success', 'Transaction Deleted Successfully');
        } catch (\Throwable $th) {
            throw $th;
        } 
    }
}
