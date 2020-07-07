@extends('layouts.app')
@section('content')
<section id="user-area">
    <a class="btn btn-primary" href="/amounts"><i class="fa fa-reply"></i> Go Back</a>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Transactions Detail</h4>
                    </div>
                </div>
                <div class="card-body px-4">
                    <div class="border-bottom mb-4">
                        <div class="align-self-center halfway-fab text-center mb-4">
                            
                        </div>
                    </div>
                    <form id="deleteForm" action="/amounts/{{$amount->id}}" method="POST">
                        @method('Delete')
                        @csrf
                    </form>
                    <form id="updateForm" action="/amounts/{{$amount->id}}" method="POST">
                        @method('PUT')
                        @csrf
                    
                        <div class="form-body">
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="text-bold-700">User</label><br>
                                    <div class="row" >
                                        <img src="{{ asset($amount->user->avatar) }}" style="margin-left: 12px" class="width-50 margin-50" alt="File not available.">
                                        <p style="margin:10px" > {{$amount->user->name}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-bold-700">Transaction Type</label><br>
                                    <p style="margin:10px" > {{$amount->reason}}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-bold-700">Amount</label><br>
                                    <p style="margin:10px" > {{$amount->amount}}</p>
                                </div>
                            </div>
                            <div class="row">
                                @if ($amount->challenge_title)
                                    <div class="col-md-4">
                                        <label class="text-bold-700">Challenge</label><br>
                                        <p style="margin:10px" > {{$amount->challenge_title}}</p>
                                        {{-- <p style="margin:10px" > {{$amount->challenge->status}}</p> --}}
                                    </div>
                                @endif
                                @if ($amount->invoice_id)
                                    <div class="col-md-4">
                                        <label class="text-bold-700">Invoice ID</label><br>
                                        <p style="margin:10px" > {{$amount->invoice_id}}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-bold-700">Invoice Type</label><br>
                                        <p style="margin:10px" > {{$amount->invoice_type}}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                    <br>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-actions left">
                                <button type="submit" form="updateForm" disable class="btn btn-raised btn-success">
                                    <i class="icon-check"></i> Upadate Challenge
                                </button>
                                <button type="submit" form="deleteForm" class="btn btn-raised btn-danger">
                                    <i class="icon-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Bids</h4>
                        <p class="card-text">Here you can see the list of bids that users made on this challenge.</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-block table-responsive">
                        <div class="row">
                            <table class="table table-striped table-bordered" id="donationsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Bid Amount</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($challenge->bids as $key => $item)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$item->user->name}}</td>
                                        <td>{{config('global.CURRENCY').$item->bid_amount}}</td>
                                        <td>{{$item->created_at->format(config('global.DATE_FORMAT') ?? '')}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

</section>
@endsection

@section('afterScript')
<script>

</script>
@endsection
