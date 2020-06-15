@extends('layouts.app')

@section('content')
<section id="minimal-statistics">
    <div class="container-fluid">
        <div class="row full-height-vh">
            <div class="col-12 d-flex align-items-center justify-content-center gradient-aqua-marine">
                <div class="card py-2 box-shadow-2 width-800">
                    <div class="card-header">
                        <div class="card-title-wrap">
                            <h4 class="card-title">Terms & Conditions</h4>
                            <p class="card-text">Following are the terms and conditions that user must accept before using the application.</p>
                        </div>
                    </div>
                    <div class="card-body collapse show">
                        <div class="card-block card-dashboard table-responsive">
                            {!! $text !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
