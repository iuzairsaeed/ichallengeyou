@extends('layouts.app')
@section('content')
<section id="user-area">
    <a class="btn btn-primary" href="/challenges"><i class="fa fa-reply"></i> Go Back</a>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Challenge Detail</h4>
                    </div>
                </div>
                <div class="card-body px-4">
                    <div class="border-bottom mb-4">
                        <div class="align-self-center halfway-fab text-center mb-4">
                            @if(strstr($challenge->file_mime, "video/"))
                                <video class="width-400" controls>
                                    <source src="{{ asset($challenge->file) }}" type="{{$challenge->file_mime}}">
                                </video>
                            @elseif(strstr($challenge->file_mime, "image/"))
                                <img src="{{ asset($challenge->file) }}" class="width-400" alt="File not available.">
                            @endif
                        </div>
                    </div>
                    <form action="/challenges/{{$challenge->id}}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Title</label>
                                        <p>{{ $challenge->title??'-' }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Location</label>
                                        <p>{{ $challenge->location??'-' }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Created At</label>
                                        <p>{{ $challenge->created_at->format(config('global.DATE_FORMAT'))??'-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="text-bold-700">Description</label>
                                        <p>{{ $challenge->description??'-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-actions left">
                                        <button type="reset" class="btn btn-raised btn-danger mr-1">
                                            <i class="icon-trash"></i> Cancel
                                        </button>
                                        <button type="submit" class="btn btn-raised btn-success">
                                            <i class="icon-check"></i> Upadate Challenge
                                        </button>
                                    </div>
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
