@extends('layouts.app')
@section('content')
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Change Password</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="px-3">
                        <form action="{{route('changePassword')}}" method="post">
                            @csrf
                            <div class="form-body col-lg-6">
                                @foreach ($errors->all() as $error)
                                    <p class="text-danger">{{ $error }}</p>
                                @endforeach
                                <div class="row">
                                    <div class="col-md-12  ">
                                        <div class="form-group">
                                            <label for="old_password">Current Password</label>
                                            <input required id="old_password" name="old_password" class="form-control border-primary" type="password" placeholder="Enter current password">
                                        </div>
                                    </div>
                                    <div class="col-md-12  ">
                                        <div class="form-group">
                                            <label for="password">New Password</label>
                                            <input required id="password" name="password" class="form-control border-primary" type="password" placeholder="Enter new password">
                                        </div>
                                    </div>
                                    <div class="col-md-12  ">
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm New Password</label>
                                            <input required id="password_confirmation" name="password_confirmation" class="form-control border-primary" type="password" placeholder="Repeat new password">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-actions left">
                                            <button type="submit" class="btn btn-raised btn-success">
                                                <i class="icon-note"></i> Update Password
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
    </div>
</section>
@endsection
