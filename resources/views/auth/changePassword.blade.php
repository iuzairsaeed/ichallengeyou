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
                <form action="{{route('changePassword')}}" method="post">
                    @csrf
                    <div class="card-body collapse show">
                        <div class="card-block card-dashboard">
                            <div class="px-3">
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
                                                <label for="new_password">New Password</label>
                                                <input required id="new_password" name="new_password" class="form-control border-primary" type="password" placeholder="Enter new password">
                                            </div>
                                        </div>
                                        <div class="col-md-12  ">
                                            <div class="form-group">
                                                <label for="new_confirm_password">Confirm New Password</label>
                                                <input required id="new_confirm_password" name="new_confirm_password" class="form-control border-primary" type="password" placeholder="Repeat new password">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 px-3">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success mr-1 btn-round shadow-z-2 btn-secondary"><i class="icon-check"></i> Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('afterScript')
<script>
    @if(session()->has('status'))
        swal('Updated',"{{session()->get('status')}}",'success');
    @endif
</script>
@endsection
