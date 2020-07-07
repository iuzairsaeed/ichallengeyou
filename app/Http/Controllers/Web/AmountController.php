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
        $orderableCols = ['created_at'];
        $searchableCols = ['type'];
        $whereChecks = [];
        $whereOps = [];
        $whereVals = [];
        $with = ['challenge','user'];
        $withCount = [];

        $data = $this->model->getData($request, $with, $withCount, $whereChecks, $whereOps, $whereVals, $searchableCols, $orderableCols);
        $serial = ($request->start ?? 0) + 1;
        collect($data['data'])->map(function ($item) use (&$serial) {
            $item['serial'] = $serial++;
            $item['amount'] = config('global.CURRENCY').$item->amount;
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
        return response($data, $data['response']);
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
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
