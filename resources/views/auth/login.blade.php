@extends('layouts.app')

@section('content')
<section id="login">
    <div class="container-fluid">
        <div class="row full-height-vh">
            <div class="col-12 d-flex align-items-center justify-content-center gradient-aqua-marine">
                <div class="card px-4 py-2 box-shadow-2 width-400">
                    <div class="card-header text-center pb-0">
                        <img src="/favicon.ico" alt="logo" class="main-logo mb-2 width-100">
                        <h4 class="text-uppercase text-bold-400 grey darken-1">Login</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-block">
                            <br />
                            <form method="POST" action="{{ route('login') }}">
                            @csrf
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="ft-at-sign"></i></span>
                                            </div>
                                            <input type="username" class="form-control form-control-lg{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" id="username" placeholder="Username" required autofocus>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="icon-key"></i></span>
                                            </div>
                                            <input type="password" class="form-control form-control-lg{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="inputPass" placeholder="Password" required>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0 ml-5">
                                                <input type="checkbox" class="custom-control-input" name="remember" id="remember">
                                                <label class="custom-control-label float-left" for="remember">Remember Me</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="text-center col-md-12">
                                        <button type="submit" class="btn btn-danger px-4 py-2 text-uppercase white font-small-4 box-shadow-2 border-0">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer grey darken-1">
                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <div class="text-center mb-1">Forgot Password? <a href="{{ route('password.request') }}"><b>Reset</b></a></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
