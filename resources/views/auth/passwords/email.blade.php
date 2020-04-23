@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row full-height-vh">
        <div class="col-12 d-flex align-items-center justify-content-center gradient-aqua-marine">
            <div class="card px-4 py-2 box-shadow-2 width-500">
                <div class="card-header text-center">
                    <img src="/favicon.ico" alt="logo" class="main-logo mb-2 width-100">
                    <h4 class="text-uppercase text-bold-400 grey darken-1">FORGOT YOUR PASSWORD?</h4>
                </div>
                <div class="card-body">
                    <div class="card-block">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <h4>Enter your email.</h4>
                            <hr />
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="email" class="form-control form-control-lg{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" id="email" placeholder="Email Address" required email autofocus>
                                </div>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row mb-0 justify-content-center">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-danger">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer grey darken-1">
                        <div class="text-center mb-1">
                            <div class="text-center mb-1">Remember Password? <a href="{{ route('login') }}"><b>Login</b></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
