@extends('layouts.app')
@section('content')
<section>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title" id="basic-layout-colored-form-control">Update User</h4>
                        <p class="mb-0">Fill the following form to update existing user.</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="px-3">
                        <form action="/users/{{$user->id}}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_premium1">Premium User</label>
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
                                            <label for="is_active1">Status</label>
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
    </div>
</section>
@endsection
