@extends('layouts.app')
@section('content')
<section id="user-area">
    <a class="btn btn-primary" href="/users"><i class="fa fa-reply"></i> Go Back</a>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <div class="card-title">User Information</div>
                    </div>
                </div>
                <div class="card-body px-4">
                    <div class="border-bottom mb-4">
                        <div class="align-self-center halfway-fab text-center">
                            <a class="profile-image">
                                <img src="{{ asset($user->avatar) }}" style="width:200px;" class="rounded-circle" alt="Card image">
                            </a>
                        </div>
                    </div>
                    <form action="/users/{{$user->id}}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700" for="name">Full Name</label>
                                        <p>{{ $user->name??'-' }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700" for="username">Username</label>
                                        <p>{{ $user->username??'-' }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700" for="email">Email</label>
                                        <p>{{ $user->email??'-' }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700" for="balance">Current Balance</label>
                                        <p>{{ $user->balance??'-' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Premium User</label>
                                        <div class="input-group">
                                            <div class="custom-control custom-radio display-inline-block pr-3">
                                                <input type="radio" class="custom-control-input" name="is_premium" id="is_premium1" value='1' {{($user->is_premium == 1) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_premium1">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio display-inline-block">
                                                <input type="radio" class="custom-control-input" name="is_premium" id="is_premium2" value='0' {{($user->is_premium == 0) ? 'checked' :'' }}>
                                                <label class="custom-control-label" for="is_premium2">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Status</label>
                                        <div class="input-group">
                                            <div class="custom-control custom-radio display-inline-block pr-3">
                                                <input type="radio" class="custom-control-input" name="is_active" id="is_active1" value='1' {{($user->is_active == 1) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active1">Active</label>
                                            </div>
                                            <div class="custom-control custom-radio display-inline-block">
                                                <input type="radio" class="custom-control-input" name="is_active" id="is_active2" value='0' {{($user->is_active == 0) ? 'checked' :'' }}>
                                                <label class="custom-control-label" for="is_active2">Deactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-actions left">
                                        <input type="hidden" id="id" name="id" value="{{$user->id}}">
                                        <button type="reset" class="btn btn-raised btn-danger mr-1">
                                            <i class="icon-trash"></i> Cancel
                                        </button>
                                        <button type="submit" class="btn btn-raised btn-success">
                                            <i class="icon-check"></i> Upadate User
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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Challenges</h4>
                        <p class="card-text">Here you can see the list of challenges that this user created.</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-block table-responsive">
                        <div class="row">
                            <table class="table table-striped table-bordered" id="challengesTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Start Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->challenges as $key => $item)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{$item->title}}</td>
                                            <td>{{$item->start_time->format('m-d-Y h:m A')}}</td>
                                            <td>{{$item->status}}</td>
                                            <td>
                                                <a href='/challenges/{{$item->id}}' class="info p-0 mr-2 success" title="View">
                                                    <i class="ft-eye font-medium-3"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Donations</h4>
                        <p class="card-text">Here you can see the list of donations that this user created.</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-block table-responsive">
                        <div class="row">
                            <table class="table table-striped table-bordered" id="donationsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->donations as $key => $item)
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{$item->challenge->title}}</td>
                                            <td>{{$item->amount}}</td>
                                            <td>{{$item->created_at->format('m-d-Y h:m A')}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('afterScript')
<script>
    $('#challengesTable').DataTable({
        columnDefs: [
            { width: "10%", "targets": [-1, 0] },
            { orderable: false, targets: [-2, -1] }
        ]
    });
    $('#donationsTable').DataTable();
</script>
@endsection
