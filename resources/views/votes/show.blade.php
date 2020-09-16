@extends('layouts.app')
@section('content')
<section id="user-area">
    <a class="btn btn-primary" href="/challenges"><i class="fa fa-reply"></i> Go Back</a>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Vote Detail</h4>
                    </div>
                </div>
                <div class="card-body px-4">
                    
                    <form id="deleteForm" action="/votes/{{$vote->id}}" method="POST">
                        @method('Delete')
                        @csrf
                    </form>
                    <form id="updateForm" action="/votes/{{$vote->id}}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Voter Name</label>
                                        <p>{{($vote->user->name ?? $vote->user->username) ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-bold-700">Challenge Name</label><br>
                                    <p> {{$vote->submitChallenges->acceptedChallenge->challenge->title}}</p>
                                </div>  
                                <div class="col-md-4">
                                    <label class="text-bold-700">Vote</label><br>
                                    <p> <?php ($vote->vote_up == true) ? print ' <i class="icon-like"></i>' : print ' <i class="icon-dislike"></i>' ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-bold-700">Created At</label><br>
                                    <p> {{$vote->created_at->format('d M, Y - h:m A')}}</p>
                                </div>  
                            </div>

                        </div>
                    </form>
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
