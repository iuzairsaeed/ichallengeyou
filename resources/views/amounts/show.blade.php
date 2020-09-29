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
                                    <label class="text-bold-700">Transaction Type</label><br>
                                    <p style="margin:10px" > {{$amount->reason}}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-bold-700">Amount</label><br>
                                    <p style="margin:10px" > {{$amount->amount}}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-bold-700">User</label><br>
                                    <div class="row" >
                                        <img src="{{ asset($amount->user->avatar) }}" style="margin-left: 12px" class="width-50 margin-50" alt="File not available.">
                                        <p style="margin:10px" > {{$amount->user->name ?? $amount->user->username}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if ($amount->challenge_title)
                                    <div class="col-md-4">
                                        <label class="text-bold-700">Challenge</label><br>
                                        <p style="margin:10px" > {{$amount->challenge_title}}</p>
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
                                    <i class="icon-check"></i> Update Challenge
                                </button>
                                <button type="submit" form="deleteForm" class="btn btn-raised btn-danger">
                                    <i class="icon-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection

@section('afterScript')
<script>

</script>
@endsection
